<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
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

        $review = KaReview::where([['id_project', $id_project],['document_type', $document_type]])->with('project')->orderBy('id', 'desc')->first();

        if($review) {
            $review_penyusun = KaReview::where([['id_project', $id_project],['document_type', $document_type],['status', 'submit-to-pemrakarsa']])
                ->orderBy('id', 'desc')
                ->first();

            $review->setAttribute('formulator_notes', $review_penyusun->notes);
        }

        /*$project = Project::where('id', $id_project)->first();
        $project->workflow_apply('submit-amdal-form-ka');
        $project->save();*/

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
            } else if($request->documentType == 'andal-rkl-rpl') {
                $document_type = 'ANDAL RKL RPL';
            } else if($request->documentType == 'ukl-upl') {
                $document_type = 'UKL UPL';
            }

            if($user > 0) {
                $user = User::where('email', $email)->first();
                Notification::send([$user], new AppKaReview($review, $document_type));
            }
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
                if($request->file) {
                    $file = $this->base64ToFile($request->file);
                    $fileName = 'ka-reviews/' . uniqid() . '.' . $file['extension'];
                    Storage::disk('public')->put($fileName, $file['file']);
                    $review->application_letter = $fileName;
                }
            }

            $review->save();

            $document_type = '';
            if($request->documentType == 'ka') {
                $document_type = 'KA';
            } else if($request->documentType == 'andal-rkl-rpl') {
                $document_type = 'ANDAL RKL RPL';
            } else if($request->documentType == 'ukl-upl') {
                $document_type = 'UKL UPL';
            }

            if($request->status == 'revisi') {
                $formulator_team = FormulatorTeam::where('id_project', $request->idProject)->first();
                if($formulator_team) {
                    $ketua = FormulatorTeamMember::where([['id_formulator_team', $formulator_team->id],['position', 'Ketua']])->first();
                    if($ketua) {
                        if($ketua->formulator) {
                            $email = $ketua->formulator->email;
                            $user = User::where('email', $email)->count();
                            if($user > 0) {
                                $user = User::where('email', $email)->first();
                                Notification::send([$user], new AppKaReview($review, $document_type));
                            }
                        }
                    }
                }
            } else {
                $feasibility_test_team = null;

                if(strtolower($project->authority) == 'pusat') {
                    $feasibility_test_team = FeasibilityTestTeam::where('authority', 'Pusat')->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                        $q->with('lukMember', 'expertBank');
                    }])->first();
                } else if(strtolower($project->authority) == 'provinsi') {
                    $feasibility_test_team = FeasibilityTestTeam::where([['authority', 'Provinsi'], ['id_province_name', $project->auth_province]])->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                    }])->first();
                } else if(strtolower($project->authority) == 'kabupaten') {
                    $feasibility_test_team = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'], ['id_district_name', $project->auth_district]])->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                    }])->first();
                }

                if($feasibility_test_team) {
                    if($feasibility_test_team->member->first()) {
                        $email = null;
                        if($feasibility_test_team->member->first()->lukMember) {
                            $email = $feasibility_test_team->member->first()->lukMember->email;
                        } else if($feasibility_test_team->member->first()->expertBank) {
                            $email = $feasibility_test_team->member->first()->expertBank->email;
                        }
                        if($email) {
                            $user = User::where('email', $email)->first();
                            if($user) {
                                Notification::send([$user], new AppKaReview($review, $document_type));
                            }
                        }
                    }
                }
                
                // === WORKFLOW === //
                if($document_type == 'KA') {
                    if($project->marking == 'amdal.form-ka-drafting') {
                        $project->workflow_apply('submit-amdal-form-ka');
                        $project->workflow_apply('review-amdal-form-ka');
                        $project->save();
                    } else if($project->marking == 'amdal.form-ka-submitted') {
                        $project->workflow_apply('review-amdal-form-ka');
                        $project->save();
                    }
                } else if($document_type == 'ANDAL RKL RPL') {
                    if($project->marking == 'amdal.form-rklrpl-drafting') {
                        $project->workflow_apply('submit-amdal');
                        $project->workflow_apply('review-amdal-adm');
                        $project->save();
                    }
                } else if($document_type == 'UKL UPL') {
                    if($project->marking == 'uklupl-mt.matrix-upl') {
                        $project->workflow_apply('submit-uklupl');
                        $project->workflow_apply('review-uklupl-adm');
                        $project->save();
                    } else if($project->marking == 'uklupl-mt.submitted') {
                        $project->workflow_apply('review-uklupl-adm');
                        $project->save();
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

    private function base64ToFile($file_64)
    {
        $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf
      
        $replace = substr($file_64, 0, strpos($file_64, ',')+1); 
      
        // find substring fro replace here eg: data:image/png;base64,
      
        $file = str_replace($replace, '', $file_64); 
      
        $file = str_replace(' ', '+', $file); 
      
        return [
            'extension' => $extension,
            'file' => base64_decode($file)
        ];
    }
}
