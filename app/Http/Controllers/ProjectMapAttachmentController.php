<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Entity\ProjectMapAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProjectMapAttachmentResource;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class ProjectMapAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = ProjectMapAttachment::all();
        if ($request->id_project) {
            $files = $files->where('id_project', $request->id_project);
            return ProjectMapAttachmentResource::collection($files);
        }
        return ProjectMapAttachmentResource::collection($files);
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
        if ($request['files']) {
            $id_project = $request['id_project'];
            $params = $request['params'];
            foreach ($request->file('files') as $i => $file) {
                $temp = json_decode($params[$i]);
                $map = ProjectMapAttachment::firstOrNew([
                    'id_project' => $id_project,
                    'attachment_type' => $temp->attachment_type,
                    'file_type' => $temp->file_type
                ]);

                if ($map->id) {
                    // unlink old files
                    if (file_exists(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename)) {
                        unlink(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename);
                    }
                }

                $map->original_filename = $file->getClientOriginalName();
                $map->stored_filename = time() . '_' . $map->id_project . '_' . uniqid('projectmap') . '.' . strtolower($file->getClientOriginalExtension());
                if ($map->attachment_type === 'ecology') {
                    $map->geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$request->geomEcologyGeojson'), 4326)");
                    $map->properties = $request->geomEcologyProperties;
                    $map->id_styles = $request->geomEcologyStyles;
                } else if ($map->attachment_type === 'social') {
                    $map->geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$request->geomSocialGeojson'), 4326)");
                    $map->properties = $request->geomSocialProperties;
                    $map->id_styles = $request->geomSocialStyles;
                } else if ($map->attachment_type === 'study') {
                    $map->geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$request->geomStudyGeojson'), 4326)");
                    $map->properties = $request->geomStudyProperties;
                    $map->id_styles = $request->geomStudyStyles;
                } else if ($map->attachment_type === 'pemantauan') {
                    $map->geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$request->geomPantauGeojson'), 4326)");
                    $map->properties = $request->geomPantauProperties;
                    $map->id_styles = $request->geomPantauStyles;
                } else if ($map->attachment_type === 'pengelolaan') {
                    $map->geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$request->geomKelolaGeojson'), 4326)");
                    $map->properties = $request->geomKelolaProperties;
                    $map->id_styles = $request->geomKelolaStyles;
                }

                if ($file->move(storage_path('app/public/map/'), $map->stored_filename)) {
                    $map->save();
                    // Add workflow
                    $project = Project::findOrFail($id_project);
                    if ($project->marking == 'uklupl-mt.matrix-upl') {
                        $project->workflow_apply('submit-uklupl');
                        $project->save();
                    }
                }
            }
            return request('success', 200);
        }
    }

    /**
     * Upload Maps.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request)
    {
        return;
    }

    /**
     * get file
     *
     *
     *
     */
    public function download($id)
    {
        // application/octet-stream

        $map = ProjectMapAttachment::where('id', $id)->first();
        if (!$map) return response('failed', 418);

        $file = storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename;
        if (!file_exists($file)) return response('File tidak ditemukan', 418);

        $headers = ['Content-Type' =>  'application/octet-stream'];
        return  Response::download($file, $map->original_filename, $headers, 'attachment');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(
            ProjectMapAttachment::where('id_project', '=', $id)->where('file_type', '=', 'SHP')->get()
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    public function getProjectMap()
    {
        $getProjectMap = DB::table('project_map_attachments')
            ->select('project_map_attachments.id_project', 'projects.project_title', 'project_map_attachments.attachment_type', 'project_map_attachments.stored_filename')
            ->leftJoin('projects', 'projects.id', '=', 'project_map_attachments.id_project')
            ->where('project_map_attachments.file_type', '=', 'SHP')
            ->get();

        return response()->json($getProjectMap);
    }

    public function getGeojson(Request $request)
    {
        $getGeojson = DB::table('project_map_attachments')
            ->select(DB::raw("json_build_object(
                'type', 'FeatureCollection',
                'crs',  json_build_object(
                    'type',      'name', 
                    'properties', json_build_object(
                        'name', 'EPSG:4326'  
                    )
                ), 
                'features', json_agg(
                    json_build_object(
                        'type', 'Feature',
                        'id', project_map_attachments.id,
                        'geometry', ST_AsGeoJSON(GEOM)::json,
                        'properties', json_build_object(
                            'id', project_map_attachments.id_project,
                            'type', attachment_type,
                            'field', properties,
                            'styles', map_styles.renderer,
                            'sector', sub_projects.sector_name
                        )
                    )
                )
            ) as feature_layer"))
            ->join('map_styles', 'project_map_attachments.id_styles', '=', 'map_styles.id')
            ->leftJoin('projects', 'projects.id', '=', 'project_map_attachments.id_project')
            ->leftJoin('sub_projects', 'sub_projects.id_project', '=', 'projects.id')
            ->when($request->has('type'), function ($query) use ($request) {
                return $query->where('attachment_type', '=', $request->type);
            })
            ->when($request->has('id'), function ($query) use ($request) {
                return $query->where('project_map_attachments.id_project', '=', $request->id);
            })
            ->whereNotNull('project_map_attachments.geom')
            ->where('project_map_attachments.file_type', '=', 'SHP')
            ->groupBy('project_map_attachments.id')
            ->get();

        return response()->json($getGeojson);
    }

    public function getMergeGeojson()
    {
        $getGeojson = DB::table('project_map_attachments')
            ->select(DB::raw("json_build_object(
                'type', 'FeatureCollection',
                'crs',  json_build_object(
                    'type',      'name', 
                    'properties', json_build_object(
                        'name', 'EPSG:4326'  
                    )
                ), 
                'features', json_agg(
                    json_build_object(
                        'type', 'Feature',
                        'id', id,
                        'geometry', ST_AsGeoJSON(GEOM)::json,
                        'properties', json_build_object(
                            'id', id_project,
                            'type', attachment_type,
                            'field', properties
                        )
                    )
                )
            ) as feature_layer"))
            ->whereNotNull('geom')
            ->groupBy('attachment_type')
            ->get();

        return response()->json($getGeojson);
    }

    public function getProjectByGeom()
    {
        $getProjectByGeom = DB::table('projects')
            ->select('projects.id', 'projects.project_title')
            ->leftJoin('project_map_attachments', 'projects.id', 'project_map_attachments.id_project')
            ->whereNotNull('project_map_attachments.geom')
            ->orderBy('projects.created_at', 'desc')
            ->groupBy('projects.id', 'projects.project_title', 'projects.created_at')
            ->get();

        return response()->json($getProjectByGeom);
    }

    public function getMapPdf(Request $request)
    {
        $getMapPdf = DB::table('project_map_attachments')
            ->select('project_map_attachments.id_project', 'project_map_attachments.file_type', 'projects.project_title', 'project_map_attachments.attachment_type', 'project_map_attachments.stored_filename')
            ->when($request->has('file_type'), function ($query) use ($request) {
                return $query->where('file_type', '=', $request->file_type);
            })
            ->when($request->has('att_type'), function ($query) use ($request) {
                return $query->where('attachment_type', '=', $request->att_type);
            })
            ->when($request->has('id_project'), function ($query) use ($request) {
                return $query->where('id_project', '=', $request->id_project);
            })
            ->leftJoin('projects', 'projects.id', '=', 'project_map_attachments.id_project')
            ->get();

        return response()->json($getMapPdf);
    }
}
