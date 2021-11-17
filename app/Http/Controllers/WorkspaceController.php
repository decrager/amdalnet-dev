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
            $etherpadUrl = env("EHTERPAD_URL"); 
            $etherpadKey = env("ETHERPAD_APIKEY");

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
}
