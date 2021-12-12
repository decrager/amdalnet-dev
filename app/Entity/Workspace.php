<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use App\Utils\Jwt;
use App\Utils\Document;
use Exception;

class Workspace
{
    private $_trackerStatus = [
        0 => 'NotFound',
        1 => 'Editing',
        2 => 'MustSave',
        3 => 'Corrupted',
        4 => 'Closed',
        6 => 'MustForceSave',
        7 => 'CorruptedForceSave'
    ];

    /**
     * Get Track statyus
     * 
     * @param Int $status
     * @return 
     */
    public function getTrackstatus($status)
    {
        return $this->_trackerStatus[$status];
    }

    /**
     * JSON Tree from headings
     *
     * @return array
     */
    public function getHeadingTree($headings)
    {
        $result = [];
        $prevdepth = null;
        $previd = null;
        while ($node = $this->getNode($headings)) {
            $result[] = $node;
        }

        return $result;
    }

    protected function getNode(&$headings)
    {
        $matches = array();
        $depth = null;
        $node = array_shift($headings);
        if (!empty($node)) {
            // var_dump($node);
            $node['name'] = $node['text'];
            $node['id'] = uniqid();
            $node['pid'] = '';
            if (preg_match('/Heading(\d)/', $node['style'], $matches)) {
                $depth = $matches[1];
            }
            // var_dump('v', $depth);
            unset($node['text']);
            unset($node['style']);
            $node['children'] = $this->getChilds($headings, $depth, $node['id']);
        }
        return $node;
    }

    protected function getNextNodeDepth($headings)
    {
        $depth = 0;
        if (count($headings)>0) {
            $node = $headings[0];
            if (preg_match('/Heading(\d)/', $node['style'], $matches)) {
                $depth = $matches[1];
            }
        }
        return $depth;
    }

    protected function getChilds(&$headings, $depth=1, $pid='')
    {
        $children = [];
        $depthNode = $this->getNextNodeDepth($headings);
        // var_dump('c', $depth, $depthNode);
        while ($depth < $depthNode) {
            $node = $this->getNode($headings);
            $node['pid'] = $pid;
            $children[] = $node;
            $depthNode = $this->getNextNodeDepth($headings);    
        }

        return $children;
    }

    /**
     * Import Heading from docx
     *
     * @return array
     */
    public function importHeadingDocx($source)
    {
        $phpWord = IOFactory::load($source);
        $result = [];
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->getElementText($element);
                if (!empty($text)) {
                    $result[] = $text;
                }
            }
        }
        return $result;
    }

    /**
     *  Get Element Text
     *
     * @return array|null
     */
    protected function getElementText($element)
    {
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $result = $element->getText();
            if (!is_string($result)) {
                var_dump('tx',$result);
            }
            return $result;
        }

        if ($element instanceof \PhpOffice\PhpWord\Element\Title) {
            $res = null;
            $text = $element->getText();
            $style = $element->getStyle();
            if (!is_string($text)) {
                return $this->getElementText($text);
            }
            if (!empty($text)) {
                return ['style' => $style, 'text' => $text];
            }
        }

        if ($element instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
            $isHeading = false;
            if (method_exists($element, 'getParagraphStyle')) {
                $style = $element->getParagraphStyle()->getStyleName();
                if (preg_match('/Heading(\d)/', $style)) {
                    $isHeading = true;
                }
            }
            if ($isHeading) {
                $res = [];
                foreach ($element->getElements() as $subElement) {
                    $tmp = $this->getElementText($subElement);
                    // var_dump('s',$tmp);
                    if (!empty($tmp)) {
                        $res[] = $tmp;
                    }
                }
                $result = implode('', $res);
                if (!empty($result)) {
                    return ['style' => $style, 'text' => $result];
                }
            }
        }

        return null;
    }


    /**
     * send command to document server editor
     * 
     * @param string $method
     * @param string $key
     * @return string 
     */
    public function commandRequest($method, $key) 
    {
        $docCommandUrl = env('OFFICE_COMMAND_URL', 'http://localhost:8008/coauthoring/CommandService.ashx');

        $arr = [
            "c" => $method,
            "key" => $key
        ];

        $headerToken = "";
        if (Jwt::isEnabled()) {
            $headerToken = Jwt::encode([ "payload" => $arr ]);  // encode a payload object into a header token
            $arr["token"] = Jwt::encode($arr);  // encode a payload object into a body token
        }

        $data = json_encode($arr);

        $opts = array('http' => array(
            'method'  => 'POST',
            'header'=> "Content-type: application/json\r\n" .
                (empty($headerToken) ? "" : "Authorization: Bearer $headerToken\r\n"),  // add a header Authorization with a header token and Authorization prefix in it
            'content' => $data
        ));

        if (substr($documentCommandUrl, 0, strlen("https")) === "https") {
           $opts['ssl'] = array( 'verify_peer'   => FALSE );
        }

        $context = stream_context_create($opts);
        $response_data = file_get_contents($documentCommandUrl, FALSE, $context);

        return $response_data;
    }

    /**
     * process save document
     * 
     * @param array|mixed $data
     * @param string $fileName
     * @return array|mixed $userAddress 
     */
    public function processSave($data, $fileName, $userAddress)
    {
        $downloadUri = $data["url"];
        if ($downloadUri === null) {
            $result["error"] = 1;
            return $result;
        }

        $curExt = strtolower('.' . pathinfo($fileName, PATHINFO_EXTENSION));  // get current file extension
        $downloadExt = strtolower('.' . pathinfo($downloadUri, PATHINFO_EXTENSION));  // get the extension of the downloaded file
        $newFileName = $fileName;

        // convert downloaded file to the file with the current extension if these extensions aren't equal
        if ($downloadExt != $curExt) {
            $key = Document::GenerateRevisionId($downloadUri);

            try {
                Log::debug('Convert ' . $downloadUri . ' from ' . $downloadExt . ' to ' . $curExt);
                $convertedUri;  // convert file and give url to a new file
                $percent = Document::GetConvertedUri($downloadUri, $downloadExt, $curExt, $key, FALSE, $convertedUri);
                if (!empty($convertedUri)) {
                    $downloadUri = $convertedUri;
                } else {
                    Log::debug("   Convert after save convertedUri is empty");
                    $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
                    $newFileName = Document::GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);  // get the correct file name if it already exists
                }
            } catch (Exception $e) {
                Log::error("   Convert after save ".$e->getMessage());
                $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
                $newFileName = Document::GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);
            }
        }

        $saved = 1;
        Log::debug('processSave: ' . $downloadUri . ' from ' . $downloadExt . ' to ' . $curExt);

        if (!(($new_data = file_get_contents($downloadUri)) === FALSE)) {
            $storagePath = Document::getStoragePath($newFileName, $userAddress);  // get the file path
            $histDir = Document::getHistoryDir($storagePath);  // get the path to the history directory
            $verDir = Document::getVersionDir($histDir, Document::getFileVersion($histDir));  // get the path to the file version
    
            mkdir($verDir);  // if the path doesn't exist, create it
    
            // get the path to the previous file version and rename the storage path with it
            rename(Document::getStoragePath($fileName, $userAddress), $verDir . DIRECTORY_SEPARATOR . "prev" . $curExt);
            Log::debug('put latest: '. $storagePath);
            file_put_contents($storagePath, $new_data, LOCK_EX);  // save file to the storage directory
    
            // save file changes to the diff.zip archive
            if ($changesData = file_get_contents($data["changesurl"])) {
                file_put_contents($verDir . DIRECTORY_SEPARATOR . "diff.zip", $changesData, LOCK_EX);
            }
    
            $histData = isset($data["changeshistory"]) ? $data["changeshistory"]:'';
            if (empty($histData)) {
                $histData = json_encode($data["history"], JSON_PRETTY_PRINT);
            }
            if (!empty($histData)) {
                // write the history changes to the changes.json file
                file_put_contents($verDir . DIRECTORY_SEPARATOR . "changes.json", $histData, LOCK_EX);  
            }
            file_put_contents($verDir . DIRECTORY_SEPARATOR . "key.txt", $data["key"], LOCK_EX);  // write the key value to the key.txt file
    
            $forcesavePath = Document::getForcesavePath($newFileName, $userAddress, false);  // get the path to the forcesaved file version
            if ($forcesavePath != "") {  // if the forcesaved file version exists
                unlink($forcesavePath);  // remove it
            }
    
            $saved = 0;
        }
    
        $result["error"] = $saved;
    
        return $result;        
    }

    /**
     * process force save document
     * 
     * @param array|mixed $data
     * @param string $fileName
     * @return array|mixed $userAddress 
     */
    function processForceSave($data, $fileName, $userAddress) {
        $downloadUri = $data["url"];
        if ($downloadUri === null) {
            $result["error"] = 1;
            return $result;
        }
    
        $curExt = strtolower('.' . pathinfo($fileName, PATHINFO_EXTENSION));  // get current file extension
        $downloadExt = strtolower('.' . pathinfo($downloadUri, PATHINFO_EXTENSION));  // get the extension of the downloaded file
        $newFileName = false;
    
        // convert downloaded file to the file with the current extension if these extensions aren't equal
        if ($downloadExt != $curExt) {
            $key = Document::GenerateRevisionId($downloadUri);
    
            try {
                Log::debug("   Convert " . $downloadUri . " from " . $downloadExt . " to " . $curExt);
                $convertedUri;  // convert file and give url to a new file
                $percent = Document::GetConvertedUri($downloadUri, $downloadExt, $curExt, $key, FALSE, $convertedUri);
                if (!empty($convertedUri)) {
                    $downloadUri = $convertedUri;
                } else {
                    Log::debug("   Convert after save convertedUri is empty");
                    $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
                    $newFileName = true;
                }
            } catch (Exception $e) {
                Log::error("   Convert after save ".$e->getMessage());
                $newFileName = true;
            }
        }
    
        $saved = 1;
    
        if (!(($new_data = file_get_contents($downloadUri)) === FALSE)) {
            $baseNameWithoutExt = substr($fileName, 0, strlen($fileName) - strlen($curExt));
            $isSubmitForm = $data["forcesavetype"] == 3;  // SubmitForm
    
            if ($isSubmitForm) {
                if ($newFileName){
                    $fileName = Document::GetCorrectName($baseNameWithoutExt . "-form" . $downloadExt, $userAddress);  // get the correct file name if it already exists
                } else {
                    $fileName = Document::GetCorrectName($baseNameWithoutExt . "-form" . $curExt, $userAddress);
                }
                $forcesavePath = Document::getStoragePath($fileName, $userAddress);
            } else {
                if ($newFileName){
                    $fileName = Document::GetCorrectName($baseNameWithoutExt . $downloadExt, $userAddress);
                }
                // create forcesave path if it doesn't exist
                $forcesavePath = Document::getForcesavePath($fileName, $userAddress, false);
                if ($forcesavePath == "") {
                    $forcesavePath = Document::getForcesavePath($fileName, $userAddress, true);
                }
            }
    
            file_put_contents($forcesavePath, $new_data, LOCK_EX);
    
            if ($isSubmitForm) {
                $uid = $data["actions"][0]["userid"];  // get the user id
                self::createMeta($fileName, $uid, "Filling Form", $userAddress);  // create meta data for the forcesaved file
            }
    
            $saved = 0;
        }
    
        $result["error"] = $saved;
    
        return $result;
    }
}
