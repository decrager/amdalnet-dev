<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TUKManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'members') {
            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $request->id)->get();
            $team_members = [];

            $num = 0;
            foreach($members as $m) {
                $nik = '-';
                $institution = null;
                $cv = null;
                $position = '-';

                if($m->lukMember) {
                    $nik = $m->lukMember->nik;
                    $institution = $m->lukMember->institution;
                    $cv = $m->lukMember->cv;
                    $position = $m->lukMember->position;
                } else if($m->expertBank) {
                    $institution = $m->expertBank->institution;
                    $cv = $m->expertBank->cv_file;
                }

                $team_members[] = [
                    'num' => $num,
                    'id' => $m->id,
                    'id_member' => $m->id_expert_bank ? $m->id_expert_bank . '-' . 'expert' : $m->id_luk_member . '-' . 'employee',
                    'idTeam' => $request->id,
                    'type' => $m->id_expert_bank != null ? 'expert' : 'employee',
                    'nik' => $nik,
                    'role' => $m->position,
                    'position' => $position,
                    'institution' => $institution,
                    'cv' => $cv
                ];

                $num++;
            }

            return $team_members;
        }

        if($request->type == 'expertEmployee') {
            $expertEmployee = [];

            $experts = ExpertBank::select('id', 'name', 'institution', 'cv_file')->orderBy('name')->get();
            $employees = LukMember::select('id', 'nik', 'name', 'institution', 'position', 'cv')->orderBy('name')->get();
            
            foreach($experts as $ex) {
                $expertEmployee[] = [
                    'id' => $ex->id,
                    'type' => 'expert',
                    'name' => $ex->name,
                    'nik' => '-',
                    'position' => '-',
                    'institution' => $ex->institution,
                    'cv' => $ex->cv_file
                ];
            }

            foreach($employees as $em) {
                $expertEmployee[] = [
                    'id' => $em->id,
                    'type' => 'employee',
                    'name' => $em->name,
                    'nik' => $em->nik,
                    'position' => $em->position,
                    'institution' => $em->institution,
                    'cv' => $em->cv
                ];
            }

            return $expertEmployee;
        }

        if($request->type == 'teamDataMember') {
            $tuk = FeasibilityTestTeam::find($request->idTeam);
            
            $team_name = null;
            if($tuk->authority == 'Pusat') {
                $team_name = 'Tim Uji Kelayakan Pusat ' . $tuk->team_number;
            } else if($tuk->authority == 'Provinsi' && $tuk->provinceAuthority) {
                $team_name = 'Tim Uji kelayakan Provinsi ' . ucwords(strtolower($tuk->provinceAuthority->name)) . ' ' . $tuk->team_number;
            } else if($tuk->authority == 'Kabupaten/Kota' && $tuk->districtAuthority) {
                $team_name = 'Tim Uji kelayakan ' . ucwords(strtolower($tuk->districtAuthority->name)) . ' ' . $tuk->team_number;
            }
            return [
                'authority' => $tuk->authority,
                'team_name' => $team_name
            ];
        }

        if($request->type == 'edit') {
            $tuk = FeasibilityTestTeam::find($request->idTeam);
            return $tuk;
        }

        if($request->type == 'list') {
            $teams = FeasibilityTestTeam::with(['member' => function($q) {
                $q->where('position', 'Ketua')->with(['expertBank', 'lukMember']);
            }, 'province', 'district', 'provinceAuthority', 'districtAuthority'])->orderBy('id', 'desc')->paginate($request->limit);
            return $teams;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->member) {
            $members = $request->members;
            $deleted = $request->deleted;

            for($i = 0; $i < count($members); $i++) {
                $member = null;

                if($members[$i]['id']) {
                    $member = FeasibilityTestTeamMember::findOrFail($members[$i]['id']);
                } else {
                    $member = new FeasibilityTestTeamMember();
                }
    
                $member->id_feasibility_test_team = $members[$i]['idTeam'];
                $member->position = $members[$i]['role'];
                
                if($members[$i]['id_member']) {
                    if($members[$i]['type'] == 'expert') {
                        $member->id_expert_bank = explode('-', $members[$i]['id_member'])[0];
                        $member->id_luk_member = null;
                    } else if($members[$i]['type'] == 'employee') {
                        $member->id_luk_member = explode('-', $members[$i]['id_member'])[0];
                        $member->id_expert_bank = null;
                    }
                }
    
                $member->save();
            }

            for($a = 0; $a < count($deleted); $a++) {
                FeasibilityTestTeamMember::destroy($deleted[$a]);
            }

            return response()->json(['message' => 'success']);
        }

        $input = $request->all();

        $validator = \Validator::make($input,[
            'authority' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'id_province' => 'required',
            'id_district' => 'required',
            'address' => 'required',
        ],[
            'authority.required' => 'Kewenangan Wajib Dipilih',
            'email.required' => 'Email Wajib Diisi',
            'phone.required' => 'Nomor Kontak Wajib Diisi',
            'id_province.required' => 'Provinsi Wajib Dipilih',
            'id_district.required' => 'Kota/Kabupaten Wajib Dipilih',
            'address.required' => 'Alamat Wajib Dipilih',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        }

        $tuk = null;

        if($request->type == 'create') {
            $tuk = new FeasibilityTestTeam();
        } else {
            $tuk = FeasibilityTestTeam::findOrFail($request->idTeam);
        }

        $tuk->authority = $request->authority;
        $tuk->assignment_number = $request->assignment_number;
        $tuk->phone = $request->phone;
        $tuk->email = $request->email;
        $tuk->id_province = $request->id_province;
        $tuk->id_district = $request->id_district;
        $tuk->address = $request->address;
        $tuk->id_province_name = $request->id_province_name;
        $tuk->id_district_name = $request->id_district_name;

        if($request->team_number == 0 || $request->team_number == null) {
            $tuk->team_number = null;
        } else {
            $tuk->team_number = $request->team_number;
        }

        if($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $name = '/assignment_letter/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            $tuk->assignment_file = Storage::url($name);
        }

        $tuk->save();

        return response()->json(['errors' => null, 'message' => 'Data successfully saved']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FeasibilityTestTeam::destroy($id);
        return response()->json(['message' => 'Data successfully deleted']);
    }
}
