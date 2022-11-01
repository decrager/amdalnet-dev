<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use App\Entity\ShowLandingTuk;
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
    public $authority=[];
    public function index()
    {
        $landingTuk = showlandingtuk::get();
        foreach ($landingTuk as $x){
            $this->authority[] = array(
                'count' => $x->count,
                'authority' => "Tim Uji Kelayakan ".$x->authority,
               );
        }
        return collect([
                'response' => 'success',
                'data' => $this->authority,
        ]);
    }
}