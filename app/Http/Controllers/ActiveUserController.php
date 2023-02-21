<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Laravue\Models\User;
use DB;
use Illuminate\Http\Request;

class ActiveUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        DB::beginTransaction();
        try {
            if($request->id && $request->active == true ){
                $user = User::find($request->id);
                $pwd = '1';
                $user->active = $pwd;
                $user->save();
                DB::commit();
                return $user;
            }
            if($request->id && $request->inactive == true ){
                $user = User::find($request->id);
                $pwd = '0';
                $user->active = $pwd;
                $user->save();
                DB::commit();
                return $user;
            }

        } catch (\Exception $th) {
            DB::rollBack();
            return response($th, 500);
        }

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
        //
    }
}
