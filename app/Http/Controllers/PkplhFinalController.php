<?php

namespace App\Http\Controllers;

use App\Entity\Initiator;
use App\Entity\OssNib;
use App\Entity\Project;
use App\Entity\ProjectPkplhFinal;
use App\Entity\ProjectSkklFinal;
use App\Services\OssService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Log;

class PkplhFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id_project) {
            $pkplhFinal = ProjectPkplhFinal::where('id_project', $request->id_project)->first();
            if ($pkplhFinal) {
                $filename = $pkplhFinal->file;
                $pkplhFinal->file = Storage::temporaryUrl('public/' . $filename, Carbon::now()->addMinutes(30));

                // update workflow
                $project = Project::findOrFail($request->id_project);
                if($project->marking == 'uklupl-mt.ba-signed') {
                    $project->workflow_apply('draft-uklupl-recommendation');
                    $project->workflow_apply('sign-uklupl-recommendation');
                    $project->workflow_apply('publish-uklupl-pkplh');
                    $project->save();
                }

                return [$pkplhFinal];
            }
            return [];
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
        $data = $request->all();
        if($request->hasFile('pkplh_final')) {
            $project = Project::findOrFail($data['id_project']);
            //create file
            $file_name = $project->project_title .' - ' . $project->initiator->name . ' - final';
            $file = $request->file('pkplh_final');
            $name = 'pkplh_final/' . $file_name . '.' . $file->extension();
            // $file->storePubliclyAs('public', $name);
            if (!Storage::disk('public')->exists('pkplh_final')) {
                Storage::disk('public')->makeDirectory('pkplh_final');
            }
            $fileCreated = Storage::disk('public')->put($name, file_get_contents($file));

            $pkplh = ProjectPkplhFinal::where('id_project', $data['id_project'])->first();
            $sendLicenseStatus = false;

            if(!$pkplh) {
                $sendLicenseStatus = true;
                $pkplh = new ProjectPkplhFinal();
                $pkplh->id_project = $data['id_project'];
                $pkplh->number = $data['number'];
                $pkplh->title = $data['title'];
                $pkplh->date_published = $data['date_published'];
            }

            $pkplh->file = $name;
            $saved = $pkplh->save();

            $initiator = Initiator::find($project->id_applicant);
            if (!$initiator) {
                Log::error('Initiator not found');
                return false;
            }
            $ossNib = OssNib::where('nib', $initiator->nib)->first();
            if (!$ossNib) {
                Log::error('OSSNib not found');
                return false;
            }
            if($ossNib) {
                $skklFinal = ProjectPkplhFinal::where('id_project', $data['id_project'])->first();
                if ($skklFinal) {
                    $filename = $skklFinal->file;
                    $fileUrl = Storage::temporaryUrl('public/' . $filename, Carbon::now()->addMinutes(30));
                    OssService::receiveLicense($request, $fileUrl, '50');
                }
            }
            // if ($ossNib) {
            //     OssService::receiveLicenseStatusNotif($request, '50');
            // }

            // $skklFinal = ProjectSkklFinal::where('id_project', $data['id_project'])->first();
            // if ($skklFinal) {
            //     $filename = $skklFinal->file;
            //     $fileUrl = Storage::temporaryUrl('public/' . $filename, Carbon::now()->addMinutes(30));
            //     OssService::receiveLicense($request, $fileUrl, '50');
            // }

            // if ($saved && $fileCreated) {
            //     if ($sendLicenseStatus) {
            //         OssService::receiveLicenseStatus($project, '50');
            //     }

            //     return response()->json(['code' => 200, 'data' => $pkplh]);
            // }
            return response()->json(['code' => 500, 'fileCreated' => $fileCreated]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }
}
