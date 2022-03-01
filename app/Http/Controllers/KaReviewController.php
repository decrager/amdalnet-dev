<?php

namespace App\Http\Controllers;

use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\KaReview;
use App\Entity\Project;
use App\Laravue\Models\User;
use App\Notifications\KaReview as AppKaReview;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class KaReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $document_type = $request->documentType;
        $id_project = $request->idProject;

        $review = KaReview::where(function($q) use($document_type, $id_project) {
            if($document_type == 'ka' || $document_type == 'ukl-upl') {
                $q->where([['id_project', $id_project],['document_type', $document_type]])->orWhere([['id_project', $id_project], ['document_type', null]]);
            } else {
                $q->where([['id_project', $id_project],['document_type', $document_type]]);
            }
        })->with('project')->orderBy('id', 'desc')->first();
        
        if($review) {
            $review_penyusun = KaReview::where(function($q) use($document_type, $id_project) {
                if($document_type == 'ka' || $document_type == 'ukl-upl') {
                    $q->where([['id_project', $id_project],['document_type', $document_type],['status', 'submit-to-pemrakarsa']])->orWhere([['id_project', $id_project], ['document_type', null]]);
                } else {
                    $q->where([['id_project', $id_project],['document_type', $document_type],['status', 'submit-to-pemrakarsa']]);
                }
            })
            ->orderBy('id', 'desc')
            ->first();

            $review->setAttribute('formulator_notes', $review_penyusun->notes);
        }
        
        return $review;
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
        if($request->type == 'penyusun') {
            $review = new KaReview();
            $review->id_project = $request->idProject;
            $review->status = 'submit-to-pemrakarsa';
            $review->notes = $request->notes;
            $review->document_type = $request->documentType;
            $review->save();

            // === NOTIFICATIONS === //
            $project = Project::findOrFail($request->idProject);
            $email = $project->initiator->email;
            $user = User::where('email', $email)->count();

            $document_type = '';
            if($request->documentType == 'ka') {
                $document_type = 'KA';
            } else if($request->documentType == 'andal') {
                $document_type = 'ANDAL';
            } else if($request->documentType == 'rkl-rpl') {
                $document_type = 'RKL RPL';
            } else if($request->documentType == 'ukl-upl') {
                $document_type = 'UKL UPL';
            }

            if($user > 0) {
                $user = User::where('email', $email)->first();
                Notification::send([$user], new AppKaReview($review, $document_type));
            }

            // === WORKFLOW === //
            // if($project->marking == 'amdal.form-ka-submitted') {
            //     $project->workflow_apply('review-amdal-form-ka');
            //     $project->save();
            // }
        }

        if($request->type == 'pemrakarsa') {
            $project = Project::findOrFail($request->idProject);

            $review = new KaReview();
            $review->id_project = $request->idProject;
            $review->status = $request->status;
            $review->document_type = $request->documentType;
            
            if($request->status == 'revisi') {
                $review->notes = $request->notes;
            } else if($request->status == 'submit') {
                if($request->hasFile('file')) {
                    if ($request->file('file')) {
                        $file = $request->file('file');
                        $fileName = 'ka-reviews/' . uniqid() . '.' . $file->extension();
                        $file->storePubliclyAs('public', $fileName);
                        $review->application_letter = Storage::url($fileName);
                    }
                }
            }

            $review->save();

            if($request->status == 'revisi') {
                $formulator_team = FormulatorTeam::where('id_project', $request->idProject)->first();
                if($formulator_team) {
                    $ketua = FormulatorTeamMember::where([['id_formulator_team', $formulator_team->id],['position', 'Ketua']])->first();
                    if($ketua) {
                        if($ketua->formulator) {
                            $email = $ketua->formulator->email;
                            $user = User::where('email', $email)->count();
                            if($user > 0) {
                                $document_type = '';
                                if($request->documentType == 'ka') {
                                    $document_type = 'KA';
                                } else if($request->documentType == 'andal') {
                                    $document_type = 'ANDAL';
                                } else if($request->documentType == 'rkl-rpl') {
                                    $document_type = 'RKL RPL';
                                } else if($request->documentType == 'ukl-upl') {
                                    $document_type = 'UKL UPL';
                                }
                                
                                $user = User::where('email', $email)->first();
                                Notification::send([$user], new AppKaReview($review, $document_type));
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Data saved successfully']);
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
