<?php

namespace App\Http\Controllers;

use App\Entity\PublicSPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicSPTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spt = PublicSPT::orderBy('id', 'desc')->get();
        return $spt;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        \Validator::make($data, [
            'name' => 'required',
            'role' => 'required',
            'nik' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'comment' => 'required',
            'worries' => 'required',
            'hope' => 'required',
            'relevance' => 'required'
        ],[
            'name.required' => 'Nama wajib diisi',
            'role.required' => 'Peran wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'phone.required' => 'No. Telepon wajib diisi',
            'email.required' => 'Email wajib diisi',
            'comment.required' => 'Saran/Komentar wajib diisi',
            'worries.required' => 'Kekhawatiran wajib diisi',
            'hope.required' => 'Harapan wajib diisi',
            'relevance.required' => 'Tingkat Relevansi wajib diisi',
        ])->validate();

        $spt = new PublicSPT();
        $spt->name = $data['name'];
        $spt->role = $data['role'];
        $spt->nik = $data['nik'];
        $spt->phone = $data['phone'];
        $spt->email = $data['email'];
        $spt->comment = $data['comment'];
        $spt->worries = $data['worries'];
        $spt->hope = $data['hope'];
        $spt->relevance = $data['relevance'];

        if($request->hasFile('photo')) {
            //create file
            $file = $request->file('photo');
            $name = 'spt/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $spt->photo = $name;
       }

        $spt->save();

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
        //
    }
}
