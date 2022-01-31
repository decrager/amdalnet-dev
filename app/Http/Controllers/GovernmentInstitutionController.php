<?php

namespace App\Http\Controllers;

use App\Entity\GovernmentInstitution;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GovernmentInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $institutions = GovernmentInstitution::orderBy('id', 'desc')->paginate($request->limit);
        return $institutions;
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
        $institutions = new GovernmentInstitution();

        if($request->type == 'update') {
            $institutions = GovernmentInstitution::findOrFail($request->id);
        }

        $institutions->name = $request->name;
        $institutions->institution_type = $request->institutionType;
        $institutions->email = $request->email;
        $institutions->id_province = $request->id_province;
        $institutions->id_district = $request->id_district;
        $institutions->save();
        
        if($request->type == 'create') {
            $is_user_exist = User::where('email', $request->email)->count();
            if($is_user_exist == 0) {
                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                $user = User::create([
                    'name' => ucfirst($request->name),
                    'email' => $request->email,
                    'password' => Hash::make('amdalnet')
                ]);
                $user->syncRoles($valsubRole);
            }
        } else {
            if($request->email != $request->old_email) {
                $is_user_exist = User::where('email', $request->old_email)->count();
                if($is_user_exist > 0) {
                    $user = User::where('email', $request->old_email)->first();
                    $user->email = $request->email;
                    $user->save();
                }
            }
        }
        
        return response()->json(['message' => 'success']);
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
        $institution = GovernmentInstitution::find($id);
        
        if($institution) {
            User::where('email', $institution->email)->delete();
            $institution->delete();
        }

        return response()->json(['message' => 'Data successfully deleted']);
    }
}
