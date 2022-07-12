<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SigapWebgisController extends Controller
{
    public function index(Request $request)
    {
        $urlPrefix = 'https://sigap.menlhk.go.id/proxy/proxy.php?https://sigap.menlhk.go.id/server/rest/services/';
        $urlPostfix = '/MapServer/0?f=json';
        if ($request->service) {
            $responseJson = Http::withOptions(['verify' => false])
                ->get($urlPrefix . $request->service . $urlPostfix)->json();
            return $responseJson;
        }
    }
}
