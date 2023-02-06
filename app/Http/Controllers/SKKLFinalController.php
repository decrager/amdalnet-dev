<?php

namespace App\Http\Controllers;

use App\Entity\Initiator;
use App\Entity\OssNib;
use App\Entity\Project;
use App\Entity\ProjectSkklFinal;
use App\Services\OssService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

class SKKLFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id_project) {
            $skklFinal = ProjectSkklFinal::where('id_project', $request->id_project)->first();
            if ($skklFinal) {
                $filename = $skklFinal->file;
                $skklFinal->file = Storage::temporaryUrl('public/' . $filename, Carbon::now()->addMinutes(30));
                return [$skklFinal];
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
        if($request->hasFile('skkl_final')) {
            $project = Project::findOrFail($data['id_project']);
            //create file
            $file_name = $project->project_title .' - ' . $project->initiator->name . ' - final';
            $file = $request->file('skkl_final');
            $name = 'skkl_final/' . $file_name . '.' . $file->extension();
            // $file->storePubliclyAs('public', $name);
            if (!Storage::disk('public')->exists('skkl_final')) {
                Storage::disk('public')->makeDirectory('skkl_final');
            }
            $fileCreated = Storage::disk('public')->put($name, file_get_contents($file));

            $skkl = ProjectSkklFinal::where('id_project', $data['id_project'])->first();
            $sendLicenseStatus = false;

            if(!$skkl) {
                $sendLicenseStatus = true;
                $skkl = new ProjectSkklFinal();
                $skkl->id_project = $data['id_project'];
                $skkl->number = $data['number'];
                $skkl->title = $data['title'];
                $skkl->date_published = $data['date_published'];
            }

            $skkl->file = $name;
            $saved = $skkl->save();
            // dd($skkl);
            $initiator = Initiator::find($project->id_applicant);
            // dd($initiator);
            // if (!$initiator) {
            //     Log::error('Initiator not found');
            //     return false;
            // }
            $ossNib = OssNib::where('nib', $initiator->nib)->first();
            // if (!$ossNib) {
            //     Log::error('OSSNib not found');
            //     return false;
            // }
            // dd($ossNib);
            if ($ossNib) {
                $skklFinal = ProjectSkklFinal::where('id_project', $data['id_project'])->first();
                if ($skklFinal) {
                    $filename = $skklFinal->file;
                    // $fileUrl = Storage::temporaryUrl('public/' . $filename);
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
            // if ($request->isOSS === "true") {
            //     OssService::receiveLicenseStatusNotif($request, '50');
            // }

            // if ($saved && $fileCreated) {
            //     if ($sendLicenseStatus) {
            //         OssService::receiveLicenseStatus($request, '50');
            //     }
            //     return response()->json(['code' => 200, 'data' => $skkl]);
            // }
            return response()->json(['code' => 500, 'fileCreated' => $fileCreated]);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
