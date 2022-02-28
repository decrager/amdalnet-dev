<?php

namespace App\Http\Controllers;

use App\Entity\OssNib;
use Illuminate\Http\Request;

class OssNibController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->nib) {
            return OssNib::where('nib', $request->nib)->first();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function show(OssNib $ossNib)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function edit(OssNib $ossNib)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OssNib $ossNib)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function destroy(OssNib $ossNib)
    {
        //
    }
}
