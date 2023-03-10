<?php

namespace App\Http\Controllers;

use App\Entity\Initiator;
use App\Http\Resources\InitiatorResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InitiatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $list = Initiator::orderBy('name', 'asc')->get();
        if (isset($params['email'])){
            $list = $list->where('email', $params['email']);
        }
        return InitiatorResource::collection($list);
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
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'name'      => 'required',
                'pic'       => 'required',
                'email'     => 'required',
                'phone'     => 'required',
                'address'   => 'required',
                'user_type' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            // return $params['password'];

            DB::beginTransaction();

            $found = User::where('email', strtolower($params['email']))->first();
            if ($found) {
                return response()->json(['error' => 'Email yang anda masukkan sudah terpakai']);
            }

            $initiatorRole = Role::findByName(Acl::ROLE_INITIATOR);
            $password = Str::random(8);
            $user_data = [
                'name' => ucfirst($params['name']),
                'email' => strtolower($params['email']),
                'password' => isset($params['password']) ? Hash::make($params['password']) : Hash::make($password),
                'oss_username' => isset($params['username']) ? $params['username'] : null
            ];

            if(!isset($params['password'])) {
                $user_data['original_password'] = $password;
            }

            $user = User::create($user_data);
            $user->syncRoles($initiatorRole);

            //create Initiator

            //logo
            $logoName = '';
            if ($request->file('fileAgencyUpload')) {
                $fileAgencyUpload = $request->file('fileAgencyUpload');
                $logoName = 'project/fileAgencyUpload/' . uniqid() . '.' . $fileAgencyUpload->extension();
                $fileAgencyUpload->storePubliclyAs('public', $logoName);

                // save logoname to user's avatar
                $user->avatar = $logoName;
                $user->save();
            }

            //fileNibDocOss
            $fileNibDocOssName = '';
            if ($request->file('fileNibDocOss')) {
                $fileNibDocOss = $request->file('fileNibDocOss');
                $fileNibDocOssName = 'project/fileNibDocOss/' . uniqid() . '.' . $fileNibDocOss->extension();
                $fileNibDocOss->storePubliclyAs('public', $fileNibDocOssName);
            }

            $initiator = Initiator::create([
                'name'        =>  $params['name'],
                'pic'         =>  $params['pic'],
                'email'       =>  $params['email'] ? strtolower($params['email']) : $params['email'],
                'phone'       =>  $params['phone'],
                'address'     =>  $params['address'],
                'user_type'   =>  $params['user_type'],
                'agency_type' =>  isset($params['agency_type']) ? $params['agency_type'] : null,
                'province'    =>  isset($params['province']) ? $params['province'] : null,
                'district'    =>  isset($params['district']) ? $params['district'] : null,
                'pic_role'    =>  isset($params['picRole']) ? $params['picRole'] : null,
                'nib'         =>  isset($params['nib']) ? $params['nib'] : null,
                'logo'        =>  $logoName,
                'nib_doc_oss' =>  $fileNibDocOssName,
            ]);



            if (!$initiator) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new InitiatorResource($initiator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Initiator  $initiator
     * @return \Illuminate\Http\Response
     */
    public function show(Initiator $initiator)
    {
        return $initiator;
    }

    public function showByEmail(Request $request)
    {
        if($request->email){
            $initiator = Initiator::where('email', $request->email)->first();

            if($initiator) {
                return $initiator;
            } else {
                return response()->json(null, 200);

            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Initiator  $initiator
     * @return \Illuminate\Http\Response
     */
    public function edit(Initiator $initiator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Initiator  $initiator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Initiator $initiator)
    {
        if ($initiator === null) {
            return response()->json(['error' => 'rona awal not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'      => 'required',
                'pic'       => 'required',
                'email'     => 'required',
                'phone'     => 'required',
                'address'   => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            $initiator->name = $params['name'];
            $initiator->pic  = $params['pic'];
            $initiator->phone  = $params['phone'];
            $initiator->address  = $params['address'];
            $initiator->nib  = $params['nib'];
            $initiator->save();
        }

        return new InitiatorResource($initiator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Initiator  $initiator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Initiator $initiator)
    {
        //
    }
}
