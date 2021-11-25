<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Entity\Workspace;
use Illuminate\Support\Facades\Log;

class WorkspaceController extends Controller
{
    /**
     * Session init
     *
     * @return \Illuminate\Http\Response
     */
    public function sessionInit(Request $request)
    {
        $currentUser = Auth::user();
        if ($currentUser) {
            $client = new \GuzzleHttp\Client();
            $etherpadUrl = env('EHTERPAD_URL'); 
            $etherpadKey = env('ETHERPAD_APIKEY');

            // author
            $reqauthor = $client->request('GET', $etherpadUrl.'/api/1/createAuthorIfNotExistsFor', ['query' => [
                'apikey' => $etherpadKey,
                'name' => $currentUser->name,
                'authorMapper' => $currentUser->id,
            ]]);
            
            $author = json_decode((string) $reqauthor->getBody(), true);
            // var_dump($author['data']['authorID']);

            // group
            $reqgroup = $client->request('GET', $etherpadUrl.'/api/1/createGroupIfNotExistsFor', ['query' => [
                'apikey' => $etherpadKey,
                'groupMapper' => 100,
            ]]);
            $group = json_decode((string) $reqgroup->getBody(), true);
            // var_dump($group['data']['groupID']); //

            // session
            $datetime = new \DateTime('now');
            $datetime->modify('+2 day');

            $reqsess = $client->request('GET', $etherpadUrl.'/api/1/createSession', ['query' => [
                'apikey' => $etherpadKey,
                'groupID' => $group['data']['groupID'],
                'authorID' => $author['data']['authorID'],
                'validUntil' => $datetime->format('U'),
            ]]);

            $sess = json_decode((string) $reqsess->getBody(), true);
            // var_dump($sess, $sess['data']['sessionID']); //
            return json_encode($sess);
        }
        return json_encode([]);
    }

    /**
     * Session init
     *
     * @return \Illuminate\Http\Response
     */
    public function importTemplate(Request $request)
    {
        $workspace = new Workspace();
        if ($request->file('file') !== null){
            //create file
            $file = $request->file('file');
            $dir = 'workspace';
            $path = $file->store($dir);
            $pathfile = Storage::path($path);
            // var_dump($path, $pathfile);
            $result = $workspace->importHeadingDocx($pathfile);
            $tree = $workspace->getHeadingTree($result);
            return response()->json($tree);
        }
    }

    /**
     * Get config for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function getConfig(Request $request, String $id) {
        $currentUser = Auth::user();
        // $officeUrl = env('MIX_OFFICE_URL'); 
        // $officeSecret = env('OFFICE_SECRET');
        $appUrl = env('APP_URL');
        $callUrl = env('OFFICE_CALLBACK_URL');
        $filename = $request->query('filename', 'sample.docx');
        $dockey = md5($filename.$id);
        $config = [
            'width' => '100%',
            'height' => '100%',
            'type' => 'desktop',
            'documentType' => 'word',
            'document' => [
                'fileType' => 'docx',
                'key' => $dockey,
                'title' => 'UKL UPL SPBU - Edit Nafila_edit FM.docx',
                // 'url' => $appUrl.'/workspace/document/download?fileName=61943e88ad99a.docx',
                'url' => $callUrl.'/storage/workspace/'.$filename,
                'permissions' => [
                    'fillForms' => true,
                    'edit' => true,
                    'modifyContentControl' => true,
                    'copy' => false,
                    'print' => false,
                    'download' => false,
                ]
            ],
            'editorConfig' => [
                'user' => [
                    'id' => 'uid.'.$currentUser->id,
                    'name' => $currentUser->name,
                ],
                'customization' => [
                    'about' => false,
                    'compactHeader' => true,
                    'compactToolbar' => true,
                    'compatibleFeatures' => true,
                    'toolbarHideFileName' => true,
                    'toolbarNoTabs' => true,
                    'hideRightMenu' => true,
                    'hideRulers' => true,
                    'help' => false,
                    'macros' => false,
                    'plugins' => false,
                    'reviewDisplay' => 'markup',
                    'customer' => [
                        'address' => 'Jakarta, KLHK',
                        'info' => '',
                        'logo' => $appUrl.'/images/logo-amdal-white.png',
                        'mail' => 'admin@amdalnet.dev',
                        'name' => 'AMDALNET',
                        'www' => 'amdalnet.dev',
                    ],
                    'logo' => [
                        'image' => $appUrl.'/images/logo-amdal-white.png',
                        'imageEmbedded' => $appUrl.'/images/logo-amdal-white.png',
                        'url' => $appUrl.'/images/logo-amdal-white.png',
                    ],
                ],
                'callbackUrl' => $callUrl.'/api/workspace/document/track?fileName='.$filename,
            ],
        ];
        return response()->json($config);
    }

    /**
     * Track from workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function track(Request $request)
    {
        Log::debug('Track DOC: '. serialize($request->query()));
        $workspace = new Workspace();
        $result['error'] = 0;
        $data = $request->json()->all();
        if (isset($data['error'])) {
            return response()->json($data);
        }
        Log::debug('Track Data: '. json_encode($data));

        $status = $workspace->getTrackStatus($data['status']);

        $userAddress = $request->query('userAddress');
        $fileName = basename($request->query('fileName'));

        switch ($status) {
            case 'Editing':  // status == 1
                if ($data['actions'] && $data['actions'][0]['type'] == 0) {   // finished edit
                    $user = $data['actions'][0]['userid'];  // the user who finished editing
                    if (array_search($user, $data['users']) === FALSE) {
                        $commandRequest = $workspace->commandRequest('forcesave', $data['key']);  // create a command request with the forcasave method
                        Log::debug('CommandRequest forcesave: ' . serialize($commandRequest));
                    }
                }
                break;
            case "MustSave":  // status == 2
            case "Corrupted":  // status == 3
                $result = $workspace->processSave($data, $fileName, $userAddress);
                break;
            case "MustForceSave":  // status == 6
            case "CorruptedForceSave":  // status == 7
                $result = $workspace->processForceSave($data, $fileName, $userAddress);
                break;
        }

        $result['status'] = 'success';
        return response()->json($result);
    }

    /**
     * Upload document for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {   
    }

    /**
     * Download document for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {

    }

    /**
     * convert document for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request)
    {   
    }

    /**
     * Delete document for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

    }

    /**
     * Files list files on workspace server
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function files(Request $request)
    {
        
    }

    /**
     * Assets list files on workspace server
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function assets(Request $request)
    {
        
    }
}
