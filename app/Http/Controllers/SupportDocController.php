<?php

namespace App\Http\Controllers;

use App\Entity\SupportDoc;
use App\Http\Resources\SupportDocResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return SupportDocResource::collection(SupportDoc::where('id_project', $request->idProject)->get());
        // return DistrictResource::collection(District::where('id_prov', $request->idProv)->get());
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
        $params = $request->all();

        //create file map
        $file = $request->file('fileDoc');
        $name = 'support-docs/' . uniqid() . '.' . $file->extension();
        $file->storePubliclyAs('public', $name);

        //create lpjp
        $supportDoc = SupportDoc::create([
            'id_project' => $params['id_project'],
            'name' => $params['name'],
            'file' => $name,
        ]);

        return new SupportDocResource($supportDoc);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\SupportDoc  $supportDoc
     * @return \Illuminate\Http\Response
     */
    public function show(SupportDoc $supportDoc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\SupportDoc  $supportDoc
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportDoc $supportDoc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\SupportDoc  $supportDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportDoc $supportDoc)
    {
        $params = $request->all();

        if($request->file !== $supportDoc->file) {
            //create file map
            $file = $request->file('fileDoc');
            $name = 'support-docs/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $supportDoc->file = $name;
        } else {
            $name = null;
        }

        //create support doc
        $supportDoc->id_project = $params['id_project'];
        $supportDoc->name = $params['name'];

        $supportDoc->save();

        return new SupportDocResource($supportDoc);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\SupportDoc  $supportDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportDoc $supportDoc)
    {
        //
    }
}
