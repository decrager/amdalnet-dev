<?php

namespace App\Http\Controllers;

use App\Entity\PublicQuestion;
use App\Http\Resources\PublicQuestionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicQuestionController extends Controller
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required'],
                'email' => ['required'],
                'question' => ['required'],
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $pubQu = PublicQuestion::create([
                'name' => $params['name'],
                'email' => $params['email'], // Just to make sure this value is unique
                'question' => $params['question'],
            ]);
            
            return new PublicQuestionResource($pubQu);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\PublicQuestion  $publicQuestion
     * @return \Illuminate\Http\Response
     */
    public function show(PublicQuestion $publicQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\PublicQuestion  $publicQuestion
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicQuestion $publicQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\PublicQuestion  $publicQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicQuestion $publicQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\PublicQuestion  $publicQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicQuestion $publicQuestion)
    {
        //
    }
}
