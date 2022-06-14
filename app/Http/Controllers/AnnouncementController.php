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
        if ($request->sort == 'null') {
            $sort = 'DESC';
        } else {
            $sort = $request->sort;
        }

        $getAllAnnouncement = Announcement::with([
            'project',
            'project.address',
            // 'project.initiator'
        ])->withCount('feedbacks')
        ->select('announcements.*')
        ->addSelect(DB::raw('coalesce(initiators.logo, users.avatar) as applicant_logo'))
        ->addSelect(DB::raw('case when announcements.end_date::timestamp::date < now()::timestamp::date then true else false end as expired'))
        ->leftJoin('initiators', 'initiators.id', '=', 'announcements.id_applicant')
        ->leftJoin('users', 'users.email', '=', 'initiators.email')
        ->where(function($query) use($request) {
            if($request->provName && ($request->provName !== null)) {
                $query->whereHas('project.address', function($q) use ($request) {
                    return $q->where('prov', '=', $request->provName);
                });
            }
            if($request->kotaName && ($request->kotaName !== null)) {
                $query->whereHas('project.address', function($q) use ($request) {
                    return $q->where('district', '=', $request->kotaName);
                });
            }
            if($request->has('keyword')) {
                $columnsToSearch = ['pic_name', 'project_type', 'project_location'];
                $searchQuery = '%' . $request->keyword . '%';
                $query->where('project_result', 'ILIKE', $searchQuery );
                foreach($columnsToSearch as $column) {
                    $query->orWhere($column, 'ILIKE', $searchQuery);
                }
            }
            return $query;
        })
        ->whereHas("project.address",function($q) use($request){
            $q->where("prov", "=", $request->provName);
        })
        ->orWhereHas("project.address",function($q) use($request){
            $q->where("district", "=", $request->kotaName);
        })
        /*
        ->when($request->has('keyword'), function ($query) use ($request) {
            $columnsToSearch = ['pic_name', 'project_result', 'project_type', 'project_location'];
            $searchQuery = '%' . $request->keyword . '%';
            $indents = $query->where('pic_name', 'ILIKE', '%'.$request->keyword.'%');
            foreach($columnsToSearch as $column) {
                $indents = $indents->orWhere($column, 'ILIKE', $searchQuery);
            }

            return $indents;
        }) */
        ->whereRaw('announcements.start_date::timestamp::date <= now()::timestamp::date')
        ->orderby('start_date', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);

        return AnnouncementResource::make($getAllAnnouncement);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnnouncementResource
     */
    public function getAnnouncementByFilter(Request $request): AnnouncementResource
    {
        if ($request->sort == 'null') {
            $sort = 'DESC';
        } else {
            $sort = $request->sort;
        }

        $getAllAnnouncement = Announcement::with([
            'project',
            //'project.province',
            'project.address'
        ])->withCount('feedbacks')
        ->select('announcements.*')
        ->addSelect(DB::raw('coalesce(initiators.logo, users.avatar) as applicant_logo'))
        ->addSelect(DB::raw('case when announcements.end_date::timestamp::date < now()::timestamp::date then true else false end as expired'))
        ->leftJoin('initiators', 'initiators.id', '=', 'announcements.id_applicant')
        ->leftJoin('users', 'users.email', '=', 'initiators.email')
        ->whereRaw('announcements.start_date::timestamp::date <= now()::timestamp::date')
        /*->whereHas("project.address",function($q) use($request){
            $q->where("prov", "=", $request->provName);
        })
        ->orWhereHas("project.address",function($q) use($request){
            $q->where("district", "=", $request->kotaName);
        })*/
        ->where(function($query) use($request) {
            if($request->provName && ($request->provName !== 'null')) {
                $query->whereHas('project.address', function($q) use ($request) {
                    return $q->where('prov', '=', $request->provName);
                });
            }
            if($request->kotaName && ($request->kotaName !== 'null')) {
                $query->whereHas('project.address', function($q) use ($request) {
                    return $q->where('district', '=', $request->kotaName);
                });
            }
            if($request->has('keyword')) {
                $columnsToSearch = ['pic_name', 'project_type', 'project_location'];
                $searchQuery = '%' . $request->keyword . '%';
                $query->where('project_result', 'ILIKE', $searchQuery );
                foreach($columnsToSearch as $column) {
                    $query->orWhere($column, 'ILIKE', $searchQuery);
                }
            }
            return $query;
        })


        ->orderby('start_date', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);
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
            $name = 'announcement/' . uniqid() . '.' . $file->extension();
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
                'proof' => $name,
                'potential_impact' => $params['potential_impact'],
                'start_date' => $params['start_date'],
                'end_date' => $params['end_date'],
                'project_id' => $params['project_id'],
                'project_result' => $params['project_result'],
                'id_applicant' => $params['id_applicant'],
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
        return Announcement::with([
            'project',
            'project.province',
            'project.address',
            'initiator',
        ])->get()->where('id', '=', $announcement->id)->first();
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
