<?php

namespace App\Http\Controllers;

use App\Entity\GovernmentInstitution;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Notifications\ChangeUserEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GovernmentInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->meeting) {
            $institutions = GovernmentInstitution::with('province', 'district')->orderBy('name')->get();
            return $institutions;
        }

        $institutions = GovernmentInstitution::where(function($q) use($request) {
            if($request->search) {
                $q->where(function($query) use($request) {
                    $query->whereRaw("LOWER(name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($query) use($request) {
                    $query->whereRaw("LOWER(institution_type) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($query) use($request) {
                    $query->whereRaw("LOWER(email) LIKE '%" . strtolower($request->search) . "%'");
                });
            }
        })->orderBy('id', 'desc')->paginate($request->limit);
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

        $request_email = $request->email ? strtolower($request->email) : $request->email;

        $institutions->name = $request->name;
        $institutions->institution_type = $request->institutionType;
        $institutions->email = $request_email;
        $institutions->id_province = $request->id_province;
        $institutions->id_district = $request->id_district;
        $institutions->save();
        
        if($request->type == 'create') {
            $is_user_exist = User::where('email', $request_email)->count();
            if($is_user_exist == 0) {
                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                $password = Str::random(8);
                $user = User::create([
                    'name' => ucfirst($request->name),
                    'email' => $request_email,
                    'password' => Hash::make($password),
                    'original_password' => $password
                ]);
                $user->syncRoles($valsubRole);
            }
        } else {
            if($request_email != $request->old_email) {
                $user = User::where('email', $request->old_email)->first();
                if($user) {
                    $password = Str::random(8);
                    $user->password = Hash::make($password);
                    $user->email = $request_email;
                    $user->save();

                    // send notification if existing user email changed
                    Notification::send([$user], new ChangeUserEmailNotification(null,null,null,$password));
                    Notification::route('mail', $request->old_email)->notify(new ChangeUserEmailNotification($user->name, $user->email, $user->roles->first()->name));
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
