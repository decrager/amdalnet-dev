<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(Request $request) {

    }

    public function getProposalCount(Request $request){
        // pemrakarsa
        $proposals = DB::table('projects')
        ->select('required_doc', DB::raw('count(*) as total'))
        ->where('id_applicant', $request->initiatorId)
        ->groupBy('required_doc')->get();

        return response($proposals);
    }
}
