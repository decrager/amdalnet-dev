<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use App\Entity\Fix2ShowLandingTuk;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Notifications\ChangeUserEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TukController extends Controller
{
    public $authority=[], $tukName=[];
    public function index(Request $request)
    {
        if($request->type == 'list') {
            $landingtuk = fix2showlandingtuk::where(function($q) use($request) {
                $search = $request->search;
                if($search) {
                    
                    $search = str_replace('tim', '', strtolower($search));
                    $search = str_replace('uji', '', $search);
                    $search = str_replace('kelayakan', '', $search);
                    $search = str_replace('provins  i', '', $search);
                    $search = trim($search);

                    $q->where(function($qu) use($search) {
                        $qu->whereRaw("LOWER(authority) LIKE '%" . strtolower($search) . "%'");                         
                    });
                }
            })            
            ->orderBy('authority', 'desc')->paginate($request->limit);
            return $landingtuk;
        }		
    }
}