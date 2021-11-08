<?php

namespace App\Http\Controllers;

use App\Entity\Announcement;
use App\Entity\Project;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use DB;

class AnnouncementController extends Controller
{
    const view_all_is_true = 1;
    const view_all_is_false = 0;

    /**
     * Display a listing of the resource.
     *
     * @return AnnouncementResource
     */
    public function index(Request $request): AnnouncementResource
    {
        if ($request->view_all == self::view_all_is_true) {
            $getAllAnnouncement = Announcement::withCount('feedbacks')
            ->when($request->has('project'), function ($query) use ($request) {
                return $query->where('project_result', '=', $request->project);
            })->orderby('start_date', 'DESC')->paginate(25);
        } else {
            $getAllAnnouncement = Announcement::withCount('feedbacks')
            ->when($request->has('project'), function ($query) use ($request) {
                return $query->where('project_result', '=', $request->project);
            })->orderby('start_date', 'DESC')->limit(10)->get();
        }

        // $getAllAnnouncement = Announcement::withCount('feedbacks')
        //     ->when($request->has('project'), function ($query) use ($request) {
        //         return $query->where('project_result', '=', $request->project);
        //     })
        //     ->orderby('start_date', 'DESC')
        //     ->when(!isset($request->limit), function ($query) use ($request) {
        //         return $query->get();
        //     })
        //     ->when($request->has('limit'), function ($query) use ($request) {
        //         return $query->paginate($request->limit ?: 10);
        //     });
        return AnnouncementResource::make($getAllAnnouncement);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'pic_name' => 'required',
                'pic_address' => 'required',
                'cs_name' => 'required',
                'cs_address' => 'required',
                'project_type' => 'required',
                'project_location' => 'required',
                'project_scale' => 'required',
                'potential_impact' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'fileProof' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file
            $file = $request->file('fileProof');
            $name = '/announcement/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            DB::beginTransaction();

            //create Announcement
            $announcement = Announcement::create([
                'pic_name' => $params['pic_name'],
                'pic_address' => $params['pic_address'],
                'cs_name' => $params['cs_name'],
                'cs_address' => $params['cs_address'],
                'project_type' => $params['project_type'],
                'project_location' => $params['project_location'],
                'project_scale' => $params['project_scale'],
                'proof' => Storage::url($name),
                'potential_impact' => $params['potential_impact'],
                'start_date' => $params['start_date'],
                'end_date' => $params['end_date'],
                'project_id' => $params['project_id'],
                'project_result' => $params['project_result'],
            ]);

            $project = Project::where('id', $params['project_id'])->first();

            $project->published = true;
            $project->save();

            if (!$announcement) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new AnnouncementResource($announcement);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Announcement $announcement
     * @return Response
     */
    public function show(Announcement $announcement)
    {
        return Announcement::with('project')->get()->where('id', '=', $announcement->id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Announcement $announcement
     * @return Response
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Announcement $announcement
     * @return Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Announcement $announcement
     * @return Response
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
