<?php

namespace App\Http\Controllers;

use App\Entity\AndalComment;
use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\FormulatorTeam;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ImportantTrait;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\PublicConsultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AndalComposingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->pdf) {
            return $this->exportKAPDF($request->idProject);
        }

        if($request->formulir) {
            return $this->formulirKa($request->idProject);
            // return $this->formulirKaPhpWord($request->idProject);
        }

        if($request->docs) {
            return $this->dokumen($request->idProject);
        }

        if($request->comment) {
            $comments = [];
            if($request->role == 'true') {
                $comments = AndalComment::where([['id_project', $request->idProject], ['id_user', $request->idUser]])->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            } else {
                $comments = AndalComment::where('id_project', $request->idProject)->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            }

            return $comments;
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvImpactAnalysis::whereHas('impactIdentification', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->orderBy('updated_at', 'DESC')->first();

            if($time) {
                return 'Diperbarui ' . $time->updated_at->locale('id')->diffForHumans();
            } else {
                return null;
            }
        }

        if($request->idProject) {
            $ids = [4,1,2,3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
                return array_search($model->getKey(),$ids);
            });
            
            $project = Project::where('id', $request->idProject)->whereHas('impactIdentificationsClone', function($query) {
                $query->whereHas('envImpactAnalysis');
            })->first();

            if($project) {
                return $this->getEnvImpactAnalysis($request->idProject, $stages);
            } else {
                return $this->getImpactNotifications($request->idProject, $stages);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->type == 'formulir') {
            if(File::exists(storage_path('app/public/formulir/' . $request->idProject . '-form-ka-andal.docx'))) {
                File::delete(storage_path('app/public/formulir/' . $request->idProject . '-form-ka-andal.docx'));
            }

            if($request->hasFile('docx')) {
                //create file
                $file = $request->file('docx');
                $name = '/formulir/' . $request->idProject . '-form-ka-andal.docx';
                $file->storePubliclyAs('public', $name);
   
                return response()->json(['message' => 'success']);
           }

           return;
        }

        if($request->type == 'impact-comment') {
            $data = $request->all();

            Validator::make($data, [
                'description' => 'required',
                'column_type' => 'required'
            ],[
                'description.required' => 'Komentar wajib diisi',
                'column_type.required' => 'Kolom wajib dipilih'
            ])->validate();

            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
            $comment->column_type = $request->column_type;
            $comment->document_type = 'andal';
            $comment->is_checked = false;
            $comment->save();

            return [
                    'id' => $comment->id,
                    'id_impact_identification' => $comment->id_impact_identification,
                    'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                    'user' => $comment->user->name,
                    'is_checked' => $comment->is_checked,
                    'description' => $comment->description,
                    'column_type' => $comment->column_type,
                    'replies' => []
            ];
        }

        if($request->type == 'impact-comment-reply') {
            $data = $request->all();

            Validator::make($data, [
                'description' => 'required',
            ],[
                'description.required' => 'Balasan wajib diisi',
            ])->validate();

            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
            $comment->document_type = 'andal';
            $comment->reply_to = $request->id_comment;
            $comment->save();

            return [
                'id' => $comment->id,
                'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'description' => $comment->description
            ];
        }

        if($request->type == 'checked-comment') {
            $comment = Comment::findOrFail($request->id);
            if($comment->is_checked) {
                $comment->is_checked = false;
            } else {
                $comment->is_checked = true;
            }
            $comment->save();

            return $comment->is_checked;
        }

        if($request->type == 'comment') {
            $comment = new AndalComment();
            $comment->id_project = $request->idProject;
            $comment->id_user = $request->idUser;
            $comment->comment = $request->comment;
            $comment->save();

            return AndalComment::whereKey($comment->id)->with('user')->first();
        }

        $analysis = $request->analysis;

        if($request->type == 'new') {
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = new EnvImpactAnalysis();
                $envAnalysis->id_impact_identifications = $analysis[$i]['id'];
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->impact_type = $analysis[$i]['impact_type'];
                $envAnalysis->studies_condition = $analysis[$i]['studies_condition'];
                $envAnalysis->condition_dev_no_plan = $analysis[$i]['condition_dev_no_plan'];
                $envAnalysis->condition_dev_with_plan = $analysis[$i]['condition_dev_with_plan'];
                $envAnalysis->impact_size_difference = $analysis[$i]['impact_size_difference'];
                $envAnalysis->save();

                $important_trait = $analysis[$i]['important_trait'];

                for($a = 0; $a < count($important_trait); $a++) {
                    $detail = new ImpactAnalysisDetail();
                    $detail->id_env_impact_analysis = $envAnalysis->id;
                    $detail->id_important_trait = $important_trait[$a]['id'];
                    $detail->important_trait = $important_trait[$a]['important_trait'];
                    $detail->description = $important_trait[$a]['desc'];
                    $detail->save();
                }
            }
        } else {
            $ids = [];
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = EnvImpactAnalysis::where('id_impact_identifications', $analysis[$i]['id'])->first();
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->impact_type = $analysis[$i]['impact_type'];
                $envAnalysis->studies_condition = $analysis[$i]['studies_condition'];
                $envAnalysis->condition_dev_no_plan = $analysis[$i]['condition_dev_no_plan'];
                $envAnalysis->condition_dev_with_plan = $analysis[$i]['condition_dev_with_plan'];
                $envAnalysis->impact_size_difference = $analysis[$i]['impact_size_difference'];
                $envAnalysis->save();

                $important_trait = $analysis[$i]['important_trait'];

                for($a = 0; $a < count($important_trait); $a++) {
                    $detail = ImpactAnalysisDetail::where([['id_env_impact_analysis', $envAnalysis->id], ['id_important_trait', $important_trait[$a]['id']]])->first();
                    $detail->important_trait = $important_trait[$a]['important_trait'];
                    $detail->description = $important_trait[$a]['desc'];
                    $detail->save();
                }

                $ids[] = $analysis[$i]['id'];
            }

            $lastTime = EnvImpactAnalysis::whereIn('id_impact_identifications', $ids)->orderBy('updated_at', 'DESC')->first()
                        ->updated_at->locale('id')->diffForHumans();

            return 'Diperbarui ' . $lastTime;
        }

        return 'Diperbarui ' . EnvImpactAnalysis::orderBy('id', 'DESC')->first()->updated_at->locale('id')->diffForHumans();
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

    private function getImpactNotifications($id_project, $stages) {
        $alphabet_list = 'A';

        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'is_hypothetical_significant')->where([['id_project', $id_project],['is_hypothetical_significant', true]])->get();

        $important_trait = ImportantTrait::select('id', 'description')->get();
        $traits = [];

        foreach($important_trait as $it) {
            $traits[] = [
                'id' => $it->id,
                'description' => $it->description,
                'desc' => null,
                'important_trait' => null
            ];
        }
        
        
        $results = [];

        foreach($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' => $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if($imp->subProjectComponent->id_project_stage) {
                    $id_stages = $imp->subProjectComponent->id_project_stage;
                } else {
                    $id_stages = $imp->subProjectComponent->component->id_project_stage;
                }

                if($id_stages == $s->id) {
                    if($imp->subProjectRonaAwal) {
                        $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                        $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                // $ronaAwal = '';
                // $component = '';

                $comments = $this->getComments($imp->id);

                $results[] = [
                    'id' => $imp->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'component' => $component,
                    'ronaAwal' => $ronaAwal,
                    'impact_size' => null,
                    'important_trait' => $traits,
                    'impact_type' => null,
                    'impact_eval_result' => null,
                    'studies_condition' => null,
                    'condition_dev_no_plan' => null,
                    'condition_dev_with_plan' => null,
                    'impact_size_difference' => null,
                    'comments' => $comments
                ];
                $results[] = [
                    'id' => $imp->id,
                    'type' => 'comments'
                ];
                $total++;
            }

            if($total == 0) {
                array_pop($results);
            } else {
                $alphabet_list++;
            }
        }
           
        return $results;
    }

    private function getEnvImpactAnalysis($id_project, $stages) {
        $alphabet_list = 'A';

        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')->where([['id_project', $id_project],['is_hypothetical_significant', true]])->get();
        $results = [];

        foreach($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' =>  $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if($imp->subProjectComponent->id_project_stage) {
                    $id_stages = $imp->subProjectComponent->id_project_stage;
                } else {
                    $id_stages = $imp->subProjectComponent->component->id_project_stage;
                }

                if($id_stages == $s->id) {
                    if($imp->subProjectRonaAwal) {
                        $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                        $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $changeType = $imp->id_change_type ? $imp->changeType->name : '';

                $comments = $this->getComments($imp->id);

                $important_trait = [];

                foreach($imp->envImpactAnalysis->detail as $det) {
                    $important_trait[] = [
                        'id' => $det->id_important_trait,
                        'description' => $det->importantTrait->description,
                        'desc' => $det->description,
                        'important_trait' => $det->important_trait
                    ];
                }

                $results[] = [
                    'id' => $imp->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'component' => $component,
                    'ronaAwal' => $ronaAwal,
                    'impact_size' => $imp->envImpactAnalysis->impact_size,
                    'important_trait' => $important_trait,
                    'impact_type' => $imp->envImpactAnalysis->impact_type,
                    'impact_eval_result' => $imp->envImpactAnalysis->impact_eval_result,
                    'studies_condition' => $imp->envImpactAnalysis->studies_condition,
                    'condition_dev_no_plan' => $imp->envImpactAnalysis->condition_dev_no_plan,
                    'condition_dev_with_plan' => $imp->envImpactAnalysis->condition_dev_with_plan,
                    'impact_size_difference' => $imp->envImpactAnalysis->impact_size_difference,
                    'comments' => $comments
                ];
                $results[] = [
                    'id' => $imp->id,
                    'type' => 'comments'
                ];
                $total++;
            }

            if($total == 0) {
                array_pop($results);
            } else {
                $alphabet_list++;
            }
        }
           
        return $results;
    }

    private function dokumen($id_project) {
        Carbon::setLocale('id');
        $project = Project::findOrFail($id_project);

        // ======== TIM PENYUSUN AMDAL ========= //
        $formulator = [];

        $formulator_team = FormulatorTeam::where('id_project', $id_project)->first();
        if($formulator_team) {
            $total = 1;
            foreach($formulator_team->member as $m) {
                $formulator[] = [
                    'tim_penyusun' => $total,
                    'name' => $m->formulator->name,
                    'position' => $m->position,
                    'cert_type' => $m->formulator->membership_status,
                    'reg_no' => $m->formulator->reg_no,
                    'cert_expire' => Carbon::createFromFormat('Y-m-d H:i:s', $m->formulator->date_end)->isoFormat('D MMMM Y'),
                    'expertise' => $m->formulator->expertise
                ];

                $total++;
            }
        }

        // ========= LPJP ============= //
        $lpjp = [
            'name' => '',
            'address' => '',
            'reg_no' => '',
            'date_start' => '',
            'date_end' => '',
            'period' => '',
            'telephone' => '',
            'faksimili' => '',
            'pic' => '',
            'position' => ''
        ];
        $lpjp_data = Lpjp::where('id', $project->id_lpjp)->first();
        if($lpjp_data) {
            $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $m->formulator->date_start);
            $date_end =  Carbon::createFromFormat('Y-m-d H:i:s', $m->formulator->date_start);
            $lpjp = [
                'name' => $lpjp_data->name,
                'address' => $lpjp_data->address . ', ' . $lpjp_data->district->name . ', Provinsi ' .$lpjp_data->province->name,
                'reg_no' => $lpjp_data->reg_no,
                'date_start' => $date_start->isoFormat('D MMMM Y'),
                'date_end' => $date_end->isoFormat('D MMMM Y'),
                'period' => 'period',
                'telephone' => $lpjp_data->phone_no,
                'faksimili' => '',
                'pic' => $lpjp_data->pic,
                'position' => ''
            ];
        }

        // ============== KA ============= //
        $ids = [4,1,2,3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
            return array_search($model->getKey(),$ids);
        });
        $impact_identification = ImpactIdentificationClone::where('id_project', $id_project)->get();
   
        $pk = [];
        $k = [];
        $o = [];
        $po = [];

        foreach($stages as $s) {
            $total = 1;
            foreach($impact_identification as $imp) {
                $ronaAwal = '';
                $component = '';

               // check stages
               $id_stages = null;

               if($imp->subProjectComponent->id_project_stage) {
                   $id_stages = $imp->subProjectComponent->id_project_stage;
               } else {
                   $id_stages = $imp->subProjectComponent->component->id_project_stage;
               }

               if($id_stages == $s->id) {
                   if($imp->subProjectRonaAwal) {
                       $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                       $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                   } else {
                       continue;
                   }
               } else {
                   continue;
               }


                if($s->name == 'Pra Konstruksi') {
                    $pk[] = [
                        'ka_pk' => $total,
                        'ka_pk_component' => $component,
                        'ka_pk_plan' => $imp->initial_study_plan,
                        'ka_pk_rona_awal' => $ronaAwal,
                        'ka_pk_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_pk_study_location' => $imp->study_location,
                        'ka_pk_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if($s->name == 'Konstruksi') {
                    $k[] = [
                        'ka_k' => $total,
                        'ka_k_component' => $component,
                        'ka_k_plan' => $imp->initial_study_plan,
                        'ka_k_rona_awal' => $ronaAwal,
                        'ka_k_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_k_study_location' => $imp->study_location,
                        'ka_k_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if($s->name == 'Operasi') {
                    $o[] = [
                        'ka_o' => $total,
                        'ka_o_component' => $component,
                        'ka_o_plan' => $imp->initial_study_plan,
                        'ka_o_rona_awal' => $ronaAwal,
                        'ka_o_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_o_study_location' => $imp->study_location,
                        'ka_o_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if($s->name == 'Pasca Operasi') {
                    $po[] = [
                        'ka_po' => $total,
                        'ka_po_component' => $component,
                        'ka_po_plan' => $imp->initial_study_plan,
                        'ka_po_rona_awal' => $ronaAwal,
                        'ka_po_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_po_study_location' => $imp->study_location,
                        'ka_po_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                }

                $total++;
            }
        }


        $templateProcessor = new TemplateProcessor('template_andal.docx');

        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('project_title_s', strtolower($project->project_title));
        $templateProcessor->setValue('project_title', ucwords(strtolower($project->project_title)));
        $templateProcessor->setValue('district', ucwords(strtolower($project->district->name)));
        $templateProcessor->setValue('province', ucwords(strtolower($project->province->name)));
        $templateProcessor->setValue('pemrakarsa_pic', $project->initiator->pic);
        $templateProcessor->setValue('address', ucwords(strtolower($project->address)));
        $templateProcessor->setValue('pemrakarsa_address', ucwords(strtolower($project->initiator->address)));
        $templateProcessor->setValue('pemrakarsa_phone', ucwords(strtolower($project->initiator->phone)));
        $templateProcessor->setValue('date_small', Carbon::now()->isoFormat('MMMM Y'));
        $templateProcessor->setValue('lpjp_name', $lpjp['name']);
        $templateProcessor->setValue('lpjp_address', $lpjp['address']);
        $templateProcessor->setValue('lpjp_reg_no', $lpjp['reg_no']);
        $templateProcessor->setValue('lpjp_date_start', $lpjp['date_start']);
        $templateProcessor->setValue('lpjp_date_end', $lpjp['date_end']);
        $templateProcessor->setValue('lpjp_period', $lpjp['period']);
        $templateProcessor->setValue('lpjp_telephone', $lpjp['telephone']);
        $templateProcessor->setValue('lpjp_faksimili', $lpjp['faksimili']);
        $templateProcessor->setValue('lpjp_pic', $lpjp['pic']);
        $templateProcessor->setValue('lpjp_position', $lpjp['position']);
        $templateProcessor->cloneRowAndSetValues('tim_penyusun', $formulator);
        $templateProcessor->cloneRowAndSetValues('ka_pk', $pk);
        $templateProcessor->cloneRowAndSetValues('ka_k', $k);
        $templateProcessor->cloneRowAndSetValues('ka_o', $o);
        $templateProcessor->cloneRowAndSetValues('ka_po', $po);

        $save_file_name = $id_project .'-andal' . '.docx'; 
        
        if (!File::exists(storage_path('app/public/workspace/'))) {
            File::makeDirectory(storage_path('app/public/workspace/'));
        }

        if (!File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));
        }
        

        return response()->json(['message' => 'success']);

    }

    private function formulirKa($id_project) {
        $ids = [4,1,2,3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
            return array_search($model->getKey(),$ids);
        });

        $project = Project::findOrFail($id_project);
        $results = [
            'project_title' => $project->project_title,
            'pic' => $project->initiator->pic,
            'description' => $project->description,
            'location_desc' => $project->location_desc
        ];

        $results['penyusun'] = [];
        $formulator = FormulatorTeam::where('id_project', $id_project)->with('member')->first();
        
        if($formulator && $formulator->member) {
            foreach($formulator->member as $f) {
                $results['penyusun'][] = [
                    'name' => $f->formulator->name,
                    'position' => $f->position
                ];
            }
        }

        $publicConsultation = PublicConsultation::select('id', 'project_id', 'positive_feedback_summary', 'negative_feedback_summary')
                                ->where('project_id', $id_project)->get();
        
        $results['positive'] = [];
        $results['negative'] = [];

        foreach($publicConsultation as $p) {
            $results['positive'][] = ['val' => $p->positive_feedback_summary ?? '']; 
            $results['negative'][] = ['val' => $p->negative_feedback_summary ?? '']; 
        }

        $im = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'initial_study_plan', 'potential_impact_evaluation', 'is_hypothetical_significant', 'study_location', 'study_length_year', 'study_length_month')
        ->where('id_project', $id_project)->get();

        $total_ms = 0;
        foreach($stages as $s) {
            $total = 0;
            $results[str_replace(' ', '_', strtolower($s->name))] = [];
            foreach($im as $pA) {
                $ronaAwal = '';
                $component = '';

                $data = $this->getComponentRonaAwal($pA, $s->id);

                if($data['component'] && $data['ronaAwal']) {
                    $ronaAwal = $data['ronaAwal'];   
                    $component = $data['component'];   
                } else {
                    continue;
                }

                $changeType = $pA->id_change_type ? $pA->changeType->name : '';
                //  $ronaAwal =  $pA->subProjectRonaAwal->id_rona_awal ? $pA->subProjectRonaAwal->ronaAwal->name : $pA->subProjectRonaAwal->name;
                //  $component = $pA->subProjectComponent->id_component ? $pA->subProjectComponent->component->name : $pA->subProjectComponent->name;

                $results[str_replace(' ', '_', strtolower($s->name))][] = [
                    'no' => $total + 1,
                    'component_name' => "$changeType $ronaAwal akibat $component",
                    'rencana' => $pA->initial_study_plan ?? '',
                    'rona_lingkungan' => $ronaAwal,
                    'dampak_potensial' => "$changeType $ronaAwal akibat $component",
                    'evaluasi_dampak' => $pA->potential_impact_evaluation ?? '',
                    'dph' => $pA->is_hypothetical_significant ? 'DPH' : 'DTPH',
                    'batas_wilayah' => $pA->study_location ?? '',
                    'batas_waktu' => $pA->study_length_year . ' tahun ' . $pA->study_length_month . ' bulan'
                ];

                if($pA->impactStudy) {
                    $results['metode_studi'][] = [
                        'no' => $total_ms + 1,
                        'potential_impact_evaluation' => "$changeType $ronaAwal akibat $component",
                        'required_information' => $pA->impactStudy->required_information ?? '',
                        'data_gathering_method' => $pA->impactStudy->data_gathering_method ?? '',
                        'analysis_method' =>  $pA->impactStudy->analysis_method ?? '',
                        'forecast_method' => $pA->impactStudy->forecast_method ?? '',
                        'evaluation_method' => $pA->impactStudy->evaluation_method ?? ''
                    ];
                    $total_ms++;
                }
                $total++;
            }
        }

        return $results;
    }

    private function formulirKaPhpWord($id_project) {
        $project = Project::findOrFail($id_project);
        
        $templateProcessor = new TemplateProcessor('template_ka_andal_2.docx');

        $templateProcessor->setValue('project_title', $project->project_title);
        $templateProcessor->setValue('pic', $project->initiator->name);
        $templateProcessor->setValue('description', $project->description);
        $templateProcessor->setValue('location_desc', $project->location_desc);
        
        $save_file_name = $id_project . '-ka-andal.docx';

        if (!File::exists(storage_path('app/public/formulir/'))) {
            File::makeDirectory(storage_path('app/public/formulir/'));
        }

        if (!File::exists(storage_path('app/public/formulir/' . $save_file_name))) {
            $templateProcessor->saveAs(storage_path('app/public/formulir/' . $save_file_name));
        }
        

        return response()->download(storage_path('app/public/formulir/' . $save_file_name));

    }

    private function getComponentRonaAwal($imp, $id_project_stage) {
        $component = null;
        $ronaAwal = null;

        if($imp->subProjectComponent) {
            if($imp->subProjectComponent->id_project_stage == $id_project_stage) {
                if($imp->subProjectRonaAwal) {
                    $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                    $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                }
            } else if($imp->subProjectComponent->id_project_stage != null) {
                if(($imp->subProjectComponent->component) && imp->subProjectComponent->component->id_project_stage == $id_project_stage) {
                    if($imp->subProjectRonaAwal) {
                        $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                        $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    }
                }
            }
        }

        return [
            'component' => $component,
            'ronaAwal' => $ronaAwal
        ];
    }

    private function exportKAPDF($idProject) {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');

        //Load word file
        $Content = IOFactory::load(storage_path('app/public/formulir/' . $idProject . '-form-ka-andal.docx'));

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $PDFWriter->save(storage_path('app/public/formulir/' . $idProject . '-form-ka-andal.pdf'));

        return response()->download(storage_path('app/public/formulir/' . $idProject . '-form-ka-andal.pdf'))->deleteFileAfterSend(false);
    }
    
    private function getComments($id) {
        $komen = Comment::where([['id_impact_identification', $id], ['document_type', 'andal'],['reply_to', null]])
                        ->orderBY('id', 'DESC')->get();
            
        $comments = [];
        foreach($komen as $c) {
            $replies = [];

            if($c->reply) {
                foreach($c->reply as $r) {
                    $replies[] = [
                        'id' => $r->id,
                        'created_at' => $r->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                        'description' => $r->description
                    ];
                }
            }

            $comments[] = [
                'id' => $c->id,
                'id_impact_identification' => $c->id_impact_identification,
                'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'user' => $c->user->name,
                'is_checked' => $c->is_checked,
                'description' => $c->description,
                'column_type' => $c->column_type,
                'replies' => $replies
            ];
        }

        return $comments;
    }
}
