<?php

namespace App\Http\Controllers;

use App\Entity\Formulator;
use App\Http\Resources\FormulatorResource;
use Illuminate\Http\Request;

class FormulatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return FormulatorResource::collection(
            Formulator::where(function ($query) use ($request) {
                return '';
            })->orderBy('id', 'DESC')->paginate(10)
        );
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
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function show(Formulator $formulator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function edit(Formulator $formulator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Formulator $formulator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formulator $formulator)
    {
        //
    }
}
