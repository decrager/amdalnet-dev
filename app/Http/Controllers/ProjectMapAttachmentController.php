<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Entity\ProjectMapAttachment;
use App\Entity\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProjectMapAttachmentResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
            if ($request->step) {
                $files = $files->where('step', $request->step);
            }
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
        DB::beginTransaction();
        try {
            if ($request['files']) {
                $params = $request['params'];
                foreach ($request->file('files') as $i => $file) {
                    $temp = json_decode($params[$i]);
                    if ($temp->attachment_type === 'ecology' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomEcologyProperties, $request->geomEcologyGeojson, $request->geomEcologyStyles);
                    }
                    if ($temp->attachment_type === 'social' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomSocialProperties, $request->geomSocialGeojson, $request->geomSocialStyles);
                    }
                    if ($temp->attachment_type === 'study' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomStudyProperties, $request->geomStudyGeojson, $request->geomStudyStyles);
                    }
                    if ($temp->attachment_type === 'pengelolaan' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomKelolaProperties, $request->geomKelolaGeojson, $request->geomKelolaStyles);
                    }
                    if ($temp->attachment_type === 'pemantauan' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomPantauProperties, $request->geomPantauGeojson, $request->geomPantauStyles);
                    }
                    if ($temp->attachment_type === 'area-pengelolaan' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomAreaKelolaProperties, $request->geomAreaKelolaGeojson, $request->geomAreaKelolaStyles);
                    }
                    if ($temp->attachment_type === 'area-pemantauan' && $temp->file_type === 'SHP') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomAreaPantauProperties, $request->geomAreaPantauGeojson, $request->geomAreaPantauStyles);
                    }
                    if ($temp->attachment_type === 'ecology' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomEcologyProperties, $request->geomEcologyGeojson, $request->geomEcologyStyles);
                    }
                    if ($temp->attachment_type === 'social' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomSocialProperties, $request->geomSocialGeojson, $request->geomSocialStyles);
                    }
                    if ($temp->attachment_type === 'study' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomStudyProperties, $request->geomStudyGeojson, $request->geomStudyStyles);
                    }
                    if ($temp->attachment_type === 'pengelolaan' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomKelolaProperties, $request->geomKelolaGeojson, $request->geomKelolaStyles);
                    }
                    if ($temp->attachment_type === 'pemantauan' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomPantauProperties, $request->geomPantauGeojson, $request->geomPantauStyles);
                    }
                    if ($temp->attachment_type === 'area-pengelolaan' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomAreaKelolaProperties, $request->geomAreaKelolaGeojson, $request->geomAreaKelolaStyles);
                    }
                    if ($temp->attachment_type === 'area-pemantauan' && $temp->file_type === 'PDF') {
                        $this->addMapAttachmentDetail($request, $temp, $file, $request->geomAreaPantauProperties, $request->geomAreaPantauGeojson, $request->geomAreaPantauStyles);
                    }
                }
                DB::commit();
                return request('success', 200);
            };
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    private function addMapAttachmentDetail($request, $temp, $file, $properties, $geometry, $idStyle)
    {
        $properties = json_decode($properties, true);
        foreach (json_decode($geometry) as $key => $kelolaGeom) {
            $encode = json_encode($kelolaGeom);
            if($temp->file_type === 'SHP') {
                if($temp->attachment_type === 'area-pemantauan' || $temp->attachment_type === 'area-pengelolaan') {
                    $map = ProjectMapAttachment::firstOrNew([
                        'id_project' => $request->id_project,
                        'attachment_type' => $temp->attachment_type,
                        'file_type' => $temp->file_type,
                        'step' => $request->step,
                        'geom' => DB::raw("ST_TRANSFORM(ST_Force2D(ST_GeomFromGeoJSON('$encode')), 4326)"),
                        'properties' => json_encode($properties[$key], true),
                    ]);
                } else {
                    $map = ProjectMapAttachment::firstOrNew([
                        'id_project' => $request->id_project,
                        'attachment_type' => $temp->attachment_type,
                        'file_type' => $temp->file_type,
                        'step' => $request->step,
                        'geom' => DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('$encode'), 4326)"),
                        'properties' => json_encode($properties[$key], true),
                    ]);
                }
            } else {
                $map = ProjectMapAttachment::firstOrNew([
                    'id_project' => $request->id_project,
                    'attachment_type' => $temp->attachment_type,
                    'file_type' => $temp->file_type,
                    'step' => $request->step,
                    'geom' => null,
                    'properties' => null,
                ]);
            }

            if ($map->id) {
                if (Storage::disk('public')->exists('map/' . $map->stored_filename)) {
                    Storage::disk('public')->delete('map/' . $map->stored_filename);
                }
            }

            $map->original_filename = $file->getClientOriginalName();
            $map->stored_filename = time() . '_' . $map->id_project . '_' . uniqid('projectmap') . '.' . strtolower($file->getClientOriginalExtension());
            if($temp->file_type === 'SHP') {
                $map->id_styles = $idStyle;
            }

            $enableCloneAndal = false;

            // save file to S3
            if (!Storage::disk('public')->exists('map')) {
                Storage::disk('public')->makeDirectory('map');
            }
            $filePut = Storage::disk('public')->put('map/' . $map->stored_filename, file_get_contents($file));
            if ($filePut) {
                $map->save();

                // clone andal, if step = 'ka'
                if ($request['step'] == 'ka' && $enableCloneAndal) {
                    $existingClone = ProjectMapAttachment::where('id_project', $request->id_project)
                        ->where('attachment_type', $temp->attachment_type)
                        ->where('file_type', $temp->file_type)
                        ->where('step', 'andal')
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if ($existingClone) {
                        // update
                        $existingClone->original_filename = $map->original_filename;
                        $existingClone->stored_filename = $map->stored_filename;
                        $existingClone->geom = $map->geom;
                        $existingClone->properties = $map->properties;
                        $existingClone->id_styles = $map->id_styles;
                        $existingClone->save();
                    } else {
                        // create new clone
                        $clone = $map->replicate();
                        $clone->step = 'andal';
                        $clone->save();
                    }
                }
                // // Add workflow
                // $project = Project::findOrFail($request->id_project);
                // if ($project->marking == 'uklupl-mt.matrix-upl') {
                //     $project->workflow_apply('submit-uklupl');
                //     $project->save();
                // }
            }
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

        // $file = storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename;
        // if (!file_exists($file)) return response('File tidak ditemukan', 418);
        if (!Storage::disk('public')->exists('map/' . $map->stored_filename)) {
            return response('File tidak ditemukan', 418);
        }

        // $headers = ['Content-Type' =>  'application/octet-stream'];
        // return  Response::download($file, $map->original_filename, $headers, 'attachment');
        // return redirect()->away(Storage::disk('public')->temporaryUrl('map/' . $map->stored_filename, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
        return redirect()->away(Storage::temporaryUrl('map/' . $map->stored_filename, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $map = ProjectMapAttachment::where('id_project', '=', $id)
        //         ->where('file_type', '=', 'SHP')
        //         ->where('step', 'andal')
        //         ->get();
        // if (count($map) == 0) {
        $map = ProjectMapAttachment::where('id_project', '=', $id)
            ->where('file_type', '=', 'SHP')
            ->get();
        // }
        foreach ($map as $item) {
            $filename = $item->stored_filename;
            $item->{"file_url"} = Storage::temporaryUrl('public/map/' . $filename, Carbon::now()->addMinutes(30));
        }
        return response()->json($map);
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
            ->where('project_map_attachments.step', 'ka')
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
                            'sector', sub_projects.sector_name,
                            'step', project_map_attachments.step
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
            ->when($request->has('step'), function ($query) use ($request) {
                return $query->where('project_map_attachments.step', '=', $request->step);
            })
            ->when($request->has('limit'), function ($query) use ($request) {
                return $query->take($request->limit);
            })
            ->where('project_map_attachments.file_type', '=', 'SHP')
            ->whereNotNull('project_map_attachments.geom')
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
            ->where('file_type', '=', 'SHP')
            ->where('step', 'ka')
            ->groupBy('attachment_type')
            ->get();

        return response()->json($getGeojson);
    }

    public function getProjectByGeom(Request $request)
    {
        $getProjectByGeom = DB::table('projects')
            ->select('projects.id', 'projects.project_title')
            ->leftJoin('project_map_attachments', 'projects.id', 'project_map_attachments.id_project')
            ->leftJoin('project_address', 'projects.id', 'project_address.id_project')
            ->whereNotNull('project_map_attachments.geom')
            ->where('project_map_attachments.step', 'ka')
            ->when($request->has('authority'), function ($query) use ($request) {
                return $query->where('projects.authority', 'ilike', '%' . $request->authority . '%');
            })
            ->when($request->has('sector'), function ($query) use ($request) {
                return $query->where('projects.sector', 'ilike', '%' . $request->sector . '%');
            })
            ->when($request->has('project_year'), function ($query) use ($request) {
                return $query->where('projects.project_year', '=', $request->project_year);
            })
            ->when($request->has('id_applicant'), function ($query) use ($request) {
                return $query->where('projects.id_applicant', '=', $request->id_applicant);
            })
            ->when($request->has('prov'), function ($query) use ($request) {
                $prov = Province::find($request->prov);
                return $query->where('project_address.prov', 'ilike', '%' . $prov->name . '%');
            })
            ->when($request->has('district'), function ($query) use ($request) {
                return $query->where('project_address.district', 'ilike', '%' . $request->district . '%');
            })
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
            ->where('project_map_attachments.step', 'ka')
            ->leftJoin('projects', 'projects.id', '=', 'project_map_attachments.id_project')
            ->get();

        return response()->json($getMapPdf);
    }

    public function getByFilename(Request $request)
    {
        $filename = $request->query('filename');
        if (!Storage::disk('public')->exists('map/' . $filename)) {
            return response('File tidak ditemukan', 418);
        }
        $fileUrl = Storage::temporaryUrl('public/map/' . $filename, Carbon::now()->addMinutes(30));
        $replaced = str_contains(dirname(__FILE__), '\app\Http\Controllers') ?
            '\app\Http\Controllers' : '/app/Http/Controllers';

        if (!File::exists(public_path('storage/map'))) {
            File::makeDirectory(public_path('storage/map'), 0775, true);
        }

        $tempFile = str_replace($replaced, '', dirname(__FILE__)) . '/public/storage/map/' . $filename;
        if (!file_exists($tempFile)) {
            // dd($tempFile);
            (File::copy($fileUrl, str_replace('\\', '/', $tempFile)));
        }
        return file_get_contents($fileUrl);
    }
}
