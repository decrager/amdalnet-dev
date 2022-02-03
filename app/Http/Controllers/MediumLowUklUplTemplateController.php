<?php

namespace App\Http\Controllers;

use App\Entity\MediumLowUklUplTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediumLowUklUplTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->sort == 'null') {
            $sort = 'DESC';
        } else {
            $sort = $request->sort;
        }

        $mediumLowUklUplTemplate = MediumLowUklUplTemplate::When($request->has('keyword'), function ($query) use ($request) {
            $searchQuery = '%' . $request->keyword . '%';
            $indents = $query->where('template_type', 'ILIKE', '%'.$request->keyword.'%');
            return $indents;
        })->orderby('id', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);

        return response()->json($mediumLowUklUplTemplate, 200);
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
                'template_type'    => 'required',
                'description'      => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 403);
        } else {
            $params = $request->all();

            //create file
            if (!empty($request->file('file'))) {
                $file = $request->file('file');
                $name = '/environmental-approval/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
            } else {
                $name = NULL;
            }
            

            //create environmental permits
            $permit = new EnvironmentalApproval();
            $permit->template_type = $params['template_type'];
            $permit->description = $params['description'];
            $permit->file = Storage::url($name);
            $permit->save();

            return new EnvironmentalExpertResource($permit);
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
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'template_type'    => 'required',
                'description'      => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $permit = EnvironmentalApproval::findOrFail($request->id);

            $params = $request->all();

            if($request->file('file') !== null){
                //create file
                $file = $request->file('file');
                $name = '/environmental-approval/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $permit->file = Storage::url($name);
            } else {
                $name = $request->file('old_file');
            }
            $permit->template_type = $params['template_type'];
            $permit->description = $params['description'];
            $permit->save();
        }

        return response()->json($permit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            EnvironmentalApproval::destroy($id);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
