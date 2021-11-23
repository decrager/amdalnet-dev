<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Entity\Workspace;

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
            $dir = 'workspace/template';
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
        $officeUrl = env('MIX_OFFICE_URL'); 
        $officeSecret = env('OFFICE_SECRET');
        $appUrl = env('APP_URL');
        $config = [
            'width' => '100%',
            'height' => '100%',
            'type' => 'desktop',
            'documentType' => 'word',
            'document' => [
                'fileType' => 'docx',
                'key' => '-1472914638',
                'title' => 'UKL UPL SPBU - Edit Nafila_edit FM.docx',
                'url' => $officeUrl.'/example/download?fileName=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=36.72.20.141__172.17.0.1',
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
                        'url' => '',
                    ],
                ],
                'callbackUrl' => $officeUrl.'/example/track?filename=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=36.72.20.141__172.17.0.1',
            ],
        ];
        return response()->json($config);
    }

    /**
     * Track for workspace editor
     *
     * @param Request $request
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function track(Request $request)
    {
        $result["error"] = 0;
        // $data = $request->
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
}
