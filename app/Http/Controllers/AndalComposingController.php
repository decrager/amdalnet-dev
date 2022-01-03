<?php

namespace App\Http\Controllers;

use App\Entity\AndalComment;
use App\Entity\Comment;
use App\Entity\ComponentType;
use App\Entity\EnvImpactAnalysis;
use App\Entity\Formulator;
use App\Entity\FormulatorTeam;
use App\Entity\HolisticEvaluation;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ImportantTrait;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\PublicConsultation;
use App\Entity\RonaAwal;
use App\Entity\SubProject;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
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
        if($request->holisticEvaluation) {
            $evaluation = HolisticEvaluation::where('id_project', $request->idProject)->count();
            if($evaluation > 0) {
                $evaluation = HolisticEvaluation::where('id_project', $request->idProject)->first();
                return $evaluation->description;
            }

            return null;
        }

        if ($request->pdf) {
            return $this->exportKAPDF($request->idProject);
        }

        if ($request->formulir) {
            return $this->formulirKa($request->idProject);
            // return $this->formulirKaPhpWord($request->idProject);
        }

        if ($request->docs) {
            return $this->dokumen($request->idProject);
        }

        if ($request->comment) {
            $comments = [];
            if ($request->role == 'true') {
                $comments = AndalComment::where([['id_project', $request->idProject], ['id_user', $request->idUser]])->orderBy('id', 'DESC')->with(['user' => function ($q) {
                    $q->select(['id', 'name']);
                }])->get();
            } else {
                $comments = AndalComment::where('id_project', $request->idProject)->orderBy('id', 'DESC')->with(['user' => function ($q) {
                    $q->select(['id', 'name']);
                }])->get();
            }

            return $comments;
        }

        if ($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if ($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvImpactAnalysis::whereHas('impactIdentification', function ($q) use ($id_project) {
                $q->where('id_project', $id_project);
            })->orderBy('updated_at', 'DESC')->first();

            if ($time) {
                return 'Diperbarui ' . $time->updated_at->locale('id')->diffForHumans();
            } else {
                return null;
            }
        }

        if ($request->idProject) {
            $ids = [4, 1, 2, 3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
                return array_search($model->getKey(), $ids);
            });

            $project = Project::where('id', $request->idProject)->whereHas('impactIdentificationsClone', function ($query) {
                $query->whereHas('envImpactAnalysis');
            })->first();

            if ($project) {
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
        if($request->type == 'holisticEvaluation') {
            $evaluation = HolisticEvaluation::where('id_project', $request->idProject)->count();
            if($evaluation > 0) {
                $evaluation = HolisticEvaluation::where('id_project', $request->idProject)->first();
                $evaluation->description = $request->description;
                $evaluation->save();
            } else {
                $evaluation = new HolisticEvaluation();
                $evaluation->id_project = $request->idProject;
                $evaluation->description = $request->description;
                $evaluation->save();
            }

            return response()->json(['message' => 'success']);
        }

        if ($request->type == 'formulir') {
            $project = Project::findOrFail($request->idProject);
            if (File::exists(storage_path('app/public/formulir/ka-andal-' . strtolower($project->project_title) . '.docx'))) {
                File::delete(storage_path('app/public/formulir/ka-andal-' . strtolower($project->project_title) . '.docx'));
            }

            if ($request->hasFile('docx')) {
                //create file
                $file = $request->file('docx');
                $name = '/formulir/ka-andal-' . strtolower($project->project_title) . '.docx';
                $file->storePubliclyAs('public', $name);

                return response()->json(['message' => 'success']);
            }

            return;
        }

        if ($request->type == 'impact-comment') {
            $data = $request->all();

            Validator::make($data, [
                'description' => 'required',
                'column_type' => 'required'
            ], [
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

        if ($request->type == 'impact-comment-reply') {
            $data = $request->all();

            Validator::make($data, [
                'description' => 'required',
            ], [
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

        if ($request->type == 'checked-comment') {
            $comment = Comment::findOrFail($request->id);
            if ($comment->is_checked) {
                $comment->is_checked = false;
            } else {
                $comment->is_checked = true;
            }
            $comment->save();

            return $comment->is_checked;
        }

        if ($request->type == 'comment') {
            $comment = new AndalComment();
            $comment->id_project = $request->idProject;
            $comment->id_user = $request->idUser;
            $comment->comment = $request->comment;
            $comment->save();

            return AndalComment::whereKey($comment->id)->with('user')->first();
        }

        $analysis = $request->analysis;

        if ($request->type == 'new') {
            for ($i = 0; $i < count($analysis); $i++) {
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

                for ($a = 0; $a < count($important_trait); $a++) {
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
            for ($i = 0; $i < count($analysis); $i++) {
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

                for ($a = 0; $a < count($important_trait); $a++) {
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

    private function getImpactNotifications($id_project, $stages)
    {
        $alphabet_list = 'A';

        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'is_hypothetical_significant')->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->get();

        $important_trait = ImportantTrait::select('id', 'description')->get();
        $traits = [];

        foreach ($important_trait as $it) {
            $traits[] = [
                'id' => $it->id,
                'description' => $it->description,
                'desc' => null,
                'important_trait' => null
            ];
        }


        $results = [];

        foreach ($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' => $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach ($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        } else {
                            continue;
                        }
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
                    'stage' => ucwords(strtolower($s->name)),
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
                    'dph' => $imp->is_hypothetical_significant === true ? 'DPH' : 'Tidak DPH',
                    'comments' => $comments
                ];
                $results[] = [
                    'id' => $imp->id,
                    'type' => 'comments'
                ];
                $total++;
            }

            if ($total == 0) {
                array_pop($results);
            } else {
                $alphabet_list++;
            }
        }

        return $results;
    }

    private function getEnvImpactAnalysis($id_project, $stages)
    {
        $alphabet_list = 'A';

        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'is_hypothetical_significant')->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->get();
        $results = [];

        foreach ($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' =>  $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach ($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $changeType = $imp->id_change_type ? $imp->changeType->name : '';

                $comments = $this->getComments($imp->id);

                $important_trait = [];

                foreach ($imp->envImpactAnalysis->detail as $det) {
                    $important_trait[] = [
                        'id' => $det->id_important_trait,
                        'description' => $det->importantTrait->description,
                        'desc' => $det->description,
                        'important_trait' => $det->important_trait
                    ];
                }

                $results[] = [
                    'id' => $imp->id,
                    'stage' => ucwords(strtolower($s->name)),
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
                    'dph' => $imp->is_hypothetical_significant === true ? 'DPH' : 'Tidak DPH',
                    'comments' => $comments
                ];
                $results[] = [
                    'id' => $imp->id,
                    'type' => 'comments'
                ];
                $total++;
            }

            if ($total == 0) {
                array_pop($results);
            } else {
                $alphabet_list++;
            }
        }

        return $results;
    }

    private function dokumen($id_project)
    {
        if (!File::exists(storage_path('app/public/workspace/'))) {
            File::makeDirectory(storage_path('app/public/workspace/'));
        }

        $save_file_name = $id_project . '-andal' . '.docx';

        if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return response()->json(['message' => 'success']);
        }

        Carbon::setLocale('id');
        $project = Project::findOrFail($id_project);

        // ======== TIM PENYUSUN AMDAL ========= //
        $formulator = [];
        $experts = [];

        $formulator_team = FormulatorTeam::where('id_project', $id_project)->first();
        if ($formulator_team) {
            $total_formulator = 1;
            $total_ahli = 1;
            foreach ($formulator_team->member as $m) {
                $formulatorName = '';
                $formulatorStatus = '';
                $formulatorRegNo = '';
                $formulatorExpired = '';
                $formulatorExpertise = '';

                if ($m->formulator) {
                    $formulatorName = $m->formulator->name;
                    $formulatorStatus = $m->formulator->membership_status;
                    $formulatorRegNo = $m->formulator->reg_no;
                    $formulatorExpired = $m->formulator->date_end ? Carbon::createFromFormat('Y-m-d H:i:s', $m->formulator->date_end)->isoFormat('D MMMM Y') : '';
                    $formulatorExpertise = $m->formulator->expertise;
                    $formulator[] = [
                        'tim_penyusun' => $total_formulator,
                        'name' => $formulatorName,
                        'position' => $m->position ? $m->position : '',
                        'cert_type' => $formulatorStatus,
                        'reg_no' => $formulatorRegNo,
                        'cert_expire' => $formulatorExpired,
                        'expertise' => $formulatorExpertise,
                    ];
                    $total_formulator++;
                } else if ($m->expert) {
                    $formulatorName = $m->expert->name;
                    $formulatorStatus = $m->expert->status;
                    $formulatorExpertise = $m->expert->expertise;
                    $experts[] = [
                        'tim_ahli' => $total_ahli,
                        'name' => $formulatorName,
                        'position' => $m->expert->status == 'Asisten' ? $m->expert->status : 'Ahli',
                        'expertise' => $m->expert->expertise
                    ];
                    $total_ahli++;
                }
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
        if ($lpjp_data) {
            $date_start = $lpjp_data->date_start ? Carbon::createFromFormat('Y-m-d H:i:s', $lpjp_data->date_start) : '';
            $date_end = $lpjp_data->date_end ? Carbon::createFromFormat('Y-m-d H:i:s', $lpjp_data->date_end) : '';
            $lpjp = [
                'name' => $lpjp_data->name,
                'address' => $lpjp_data->address . ', ' . $lpjp_data->district->name . ', Provinsi ' . $lpjp_data->province->name,
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

        // ============== KA DAN MATRIKS DPH ============= //
        $ids = [4, 1, 2, 3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
            return array_search($model->getKey(), $ids);
        });
        $impact_identification = ImpactIdentificationClone::where('id_project', $id_project)->with(['potentialImpactEvaluation', 'envImpactAnalysis.detail.importantTrait'])->get();

        $pk = [];
        $k = [];
        $o = [];
        $po = [];
        $dp_pk = [];
        $dp_pk_name = [];
        $dp_k = [];
        $dp_k_name = [];
        $dp_o = [];
        $dp_o_name = [];
        $dp_po = [];
        $dp_po_name = [];
        $dph_pk = [];
        $dph_k = [];
        $dph_o = [];
        $dph_po = [];
        $mdph_pk = [];
        $mdph_pk_name = [];
        $mdph_k = [];
        $mdph_k_name = [];
        $mdph_o = [];
        $mdph_o_name = [];
        $mdph_po = [];
        $mdph_po_name = [];
        $bwk_pk = [];
        $bwk_k = [];
        $bwk_o = [];
        $bwk_po = [];
        $com_pk = [];
        $com_pk_name = [];
        $com_k = [];
        $com_k_name = [];
        $com_o = [];
        $com_o_name = [];
        $com_po = [];
        $com_po_name = [];
        $pk_bwk = [];
        $k_bwk = [];
        $o_bwk = [];
        $po_bwk = [];
        $dpg_pk_block = [];
        $dpg_pk_block_name = [];

        foreach ($stages as $s) {
            $total = 1;
            $stage_id = null;
            foreach ($impact_identification as $imp) {
                $ronaAwal = '';
                $component = '';
                $stage_merge = '';

                if($stage_id == $s->id) {
                    $stage_merge = '<w:vMerge w:val="continue"/>';
                } else {
                    $stage_merge = '<w:vMerge w:val="restart"/>';
                }

                // check stages
                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $component_type = $this->getComponentType($imp);

                // ======= POTENTIAL IMPACT EVALUATIONS ======= //
                $ed_besaran_rencana = '';
                $ed_kondisi_rona = '';
                $ed_pengaruh_rencana = '';
                $ed_intensitas_perhatian = '';
                $ed_kesimpulan = '';

                if ($imp->potentialImpactEvaluation) {
                    foreach ($imp->potentialImpactEvaluation as $pI) {
                        if ($pI->id_pie_param == 1) {
                            $ed_besaran_rencana = $pI->text;
                        } else if ($pI->id_pie_param == 2) {
                            $ed_kondisi_rona = $pI->text;
                        } else if ($pI->id_pie_param == 3) {
                            $ed_pengaruh_rencana = $pI->text;
                        } else if ($pI->id_pie_param == 4) {
                            $ed_intensitas_perhatian = $pI->text;
                        } else if ($pI->id_pie_param == 5) {
                            $ed_kesimpulan = $pI->text;
                        }
                    }
                }

                $change_type = $imp->id_change_type ? $imp->changeType->name : '';

                // PENENTUAN SIFAT PENTING DAMPAK
                $impact_result = '';
                $table_with_no_plan = [
                    'studies' => '',
                    'no_plan' => '',
                    'with_plan' => '',
                    'size_differ' => ''
                ];
                $important_trait_1 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_2 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_3 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_4 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_5 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_6 = ['nilai' => '', 'keterangan' => ''];
                $important_trait_7 = ['nilai' => '', 'keterangan' => ''];

                if ($s->name == 'Pra Konstruksi') {

                    // MATRIKS DP
                    if (!in_array($component, $dp_pk_name)) {
                        $dp_pk_name[] = $component;
                        $dp_pk[] = [
                            'dp_pk' => 'Tahap ' . $s->name . $stage_merge,
                            'dp_pk_component' => $component
                        ];
                    }

                    // MATRIKS DPH & COMPONENT
                    if (!in_array($component, $mdph_pk_name)) {
                        $mdph_pk_name[] = $component;
                        $mdph_pk[] = [
                            'mdph_pk' => 'Tahap ' . $s->name . $stage_merge,
                            'mdph_pk_component' => $component
                        ];
                    }

                    // COMPONENT
                    if (!in_array($component, $com_pk_name)) {
                        $com_pk_name[] = $component;
                        $com_pk[] = [
                            'com_pk_name' => '2.1.' . count($com_pk_name) . ' ' . $component
                        ];
                    }

                    // PRAKIRAAN DAMPAK PENTING
                    if($imp->is_hypothetical_significant) {
                        $impact_result = '';
                        if($imp->envImpactAnalysis) {
                            $impact_result = $imp->envImpactAnalysis->impact_eval_result ?? '';
                            $table_with_no_plan = [
                                'studies' => $imp->envImpactAnalysis->studies_condition,
                                'no_plan' => $imp->envImpactAnalysis->condition_dev_no_plan,
                                'with_plan' => $imp->envImpactAnalysis->condition_dev_with_plan,
                                'size_differ' => $imp->envImpactAnalysis->impact_size_difference
                            ];
                            if($imp->envImpactAnalysis->detail) {
                                if($imp->envImpactAnalysis->detail->first()) {
                                    foreach($imp->envImpactAnalysis->detail as $det) {
                                        if($det->id_important_trait == 1) {
                                            $important_trait_1['nilai'] = $det->important_trait;
                                            $important_trait_1['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 2) {
                                            $important_trait_2['nilai'] = $det->important_trait;
                                            $important_trait_2['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 3) {
                                            $important_trait_3['nilai'] = $det->important_trait;
                                            $important_trait_3['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 4) {
                                            $important_trait_4['nilai'] = $det->important_trait;
                                            $important_trait_4['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 5) {
                                            $important_trait_5['nilai'] = $det->important_trait;
                                            $important_trait_5['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 6) {
                                            $important_trait_6['nilai'] = $det->important_trait;
                                            $important_trait_6['keterangan'] = $det->description;
                                        } else if($det->id_important_trait == 7) {
                                            $important_trait_7['nilai'] = $det->important_trait;
                                            $important_trait_7['keterangan'] = $det->description;
                                        }
                                    }
                                }
                            }
                        }
                        if(!in_array($component, $dpg_pk_block_name)) {

                            $dpg_pk_block_name[] = $component;
                            $dpg_pk_block[] = [
                                'dpg_pk_component' => $component,
                                'dpg_pk_imp_block' => '${dpg_pk_imp_block_'.count($dpg_pk_block_name).'}',
                                '/dpg_pk_imp_block' => '${/dpg_pk_imp_block_'.count($dpg_pk_block_name).'}',
                                'dampak' => [$change_type . ' ' . $ronaAwal],
                                'table_with_no_plan' => [$table_with_no_plan],
                                'impact_result' => [strtolower($impact_result)],
                                'important_trait' => [
                                    [
                                        $important_trait_1,
                                        $important_trait_2,
                                        $important_trait_3,
                                        $important_trait_4,
                                        $important_trait_5,
                                        $important_trait_6,
                                        $important_trait_7
                                    ]
                                ]
                            ];
                        } else {
                            $index = array_search($component, $dpg_pk_block_name);
                            $dpg_pk_block[$index]['dampak'][] = $change_type . ' ' . $ronaAwal;
                            $dpg_pk_block[$index]['table_with_no_plan'][] = $table_with_no_plan;
                            $dpg_pk_block[$index]['impact_result'][] = strtolower($impact_result);
                            $dpg_pk_block[$index]['important_trait'][] = [
                                $important_trait_1,
                                $important_trait_2,
                                $important_trait_3,
                                $important_trait_4,
                                $important_trait_5,
                                $important_trait_6,
                                $important_trait_7
                            ];
                        }
                        
                        // DESKRIPSI BATAS WILAYAH STUDI
                        if(strtolower($component_type) == 'geofisik kimia' || strtolower($component_type) == 'biologi') {
                            $pk_bwk[] = [
                                'pk_bwk' => $ronaAwal . ' akibat ' . $component,
                                'pk_bwk_ba' => ''
                            ];
                        }
                    }

                    $pk[] = [
                        'ka_pk' => $total,
                        'dp_pk' => $total,
                        'ka_pk_component' => $component,
                        'ka_pk_plan' => $imp->initial_study_plan,
                        'ka_pk_rona_awal' => $ronaAwal,
                        'ka_pk_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_pk_study_location' => $imp->study_location,
                        'ka_pk_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'ka_pk_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'ka_pk_ed_besaran_rencana' => $ed_besaran_rencana,
                        'ka_pk_ed_kondisi_rona' => $ed_kondisi_rona,
                        'ka_pk_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'ka_pk_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'ka_pk_ed_kesimpulan' => $ed_kesimpulan,
                        'ka_pk_env_impact' => "$change_type $ronaAwal"
                    ];

                    // MATRIKS DPH
                    $dph_pk[] = [
                        'dph_pk' => $total,
                        'dph_pk_component' => $component,
                        'dph_pk_plan' => $imp->initial_study_plan,
                        'dph_pk_rona_awal' => $ronaAwal,
                        'dph_pk_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'dph_pk_study_location' => $imp->study_location,
                        'dph_pk_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'dph_pk_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'dph_pk_ed_besaran_rencana' => $ed_besaran_rencana,
                        'dph_pk_ed_kondisi_rona' => $ed_kondisi_rona,
                        'dph_pk_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'dph_pk_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'dph_pk_ed_kesimpulan' => $ed_kesimpulan,
                        'dph_pk_env_impact' => "$change_type $ronaAwal"
                    ];

                    // BATAS WAKTU KAJIAN
                    $bwk_pk[] = [
                        'bwk_pk' => $total,
                        'bwk_pk_component' => $component,
                        'bwk_pk_env_impact' => "$change_type $ronaAwal",
                        'bwk_pk_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if ($s->name == 'Konstruksi') {
                    // MATRIKS DP
                    if (!in_array($component, $dp_k_name)) {
                        $dp_k_name[] = $component;
                        $dp_k[] = [
                            'dp_k' => 'Tahap ' . $s->name . $stage_merge,
                            'dp_k_component' => $component
                        ];
                    }

                    // MATRIKS DPH
                    if (!in_array($component, $mdph_k_name)) {
                        $mdph_k_name[] = $component;
                        $mdph_k[] = [
                            'mdph_k' => 'Tahap ' . $s->name . $stage_merge,
                            'mdph_k_component' => $component
                        ];
                    }

                    // COMPONENT
                    if (!in_array($component, $com_k_name)) {
                        $com_k_name[] = $component;
                        $com_k[] = [
                            'com_k_name' => '2.2.' . count($com_k_name) . ' ' . $component
                        ];
                    }

                    // DESKRIPSI BATAS WILAYAH STUDI
                    if(strtolower($component_type) == 'geofisik kimia' || strtolower($component_type) == 'biologi') {
                        $k_bwk[] = [
                            'k_bwk' => $ronaAwal . ' akibat ' . $component,
                            'k_bwk_ba' => ''
                        ];
                    }

                    $k[] = [
                        'ka_k' => $total,
                        'ka_k_component' => $component,
                        'ka_k_plan' => $imp->initial_study_plan,
                        'ka_k_rona_awal' => $ronaAwal,
                        'ka_k_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_k_study_location' => $imp->study_location,
                        'ka_k_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'ka_k_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'ka_k_ed_besaran_rencana' => $ed_besaran_rencana,
                        'ka_k_ed_kondisi_rona' => $ed_kondisi_rona,
                        'ka_k_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'ka_k_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'ka_k_ed_kesimpulan' => $ed_kesimpulan,
                        'ka_k_env_impact' => "$change_type $ronaAwal"
                    ];

                    // MATRIKS DPH
                    $dph_k[] = [
                        'dph_k' => $total,
                        'dph_k_component' => $component,
                        'dph_k_plan' => $imp->initial_study_plan,
                        'dph_k_rona_awal' => $ronaAwal,
                        'dph_k_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'dph_k_study_location' => $imp->study_location,
                        'dph_k_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'dph_k_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'dph_k_ed_besaran_rencana' => $ed_besaran_rencana,
                        'dph_k_ed_kondisi_rona' => $ed_kondisi_rona,
                        'dph_k_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'dph_k_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'dph_k_ed_kesimpulan' => $ed_kesimpulan,
                        'dph_k_env_impact' => "$change_type $ronaAwal"
                    ];

                    // BATAS WAKTU KAJIAN
                    $bwk_k[] = [
                        'bwk_k' => $total,
                        'bwk_k_component' => $component,
                        'bwk_k_env_impact' => "$change_type $ronaAwal",
                        'bwk_k_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if ($s->name == 'Operasi') {
                    // MATRIKS DP
                    if (!in_array($component, $dp_o_name)) {
                        $dp_o_name[] = $component;
                        $dp_o[] = [
                            'dp_o' => 'Tahap ' . $s->name . $stage_merge,
                            'dp_o_component' => $component
                        ];
                    }

                    // MATRIKS DPH
                    if (!in_array($component, $mdph_o_name)) {
                        $mdph_o_name[] = $component;
                        $mdph_o[] = [
                            'mdph_o' => 'Tahap ' . $s->name . $stage_merge,
                            'mdph_o_component' => $component
                        ];
                    }

                    // COMPONENT
                    if (!in_array($component, $com_o_name)) {
                        $com_o_name[] = $component;
                        $com_o[] = [
                            'com_o_name' => '2.3.' . count($com_o_name) . ' ' . $component
                        ];
                    }

                    // DESKRIPSI BATAS WILAYAH STUDI
                    if(strtolower($component_type) == 'geofisik kimia' || strtolower($component_type) == 'biologi') {
                        $o_bwk[] = [
                            'o_bwk' => $ronaAwal . ' akibat ' . $component,
                            'o_bwk_ba' => ''
                        ];
                    }

                    $o[] = [
                        'ka_o' => $total,
                        'ka_o_component' => $component,
                        'ka_o_plan' => $imp->initial_study_plan,
                        'ka_o_rona_awal' => $ronaAwal,
                        'ka_o_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_o_study_location' => $imp->study_location,
                        'ka_o_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'ka_o_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'ka_o_ed_besaran_rencana' => $ed_besaran_rencana,
                        'ka_o_ed_kondisi_rona' => $ed_kondisi_rona,
                        'ka_o_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'ka_o_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'ka_o_ed_kesimpulan' => $ed_kesimpulan,
                        'ka_o_env_impact' => "$change_type $ronaAwal"
                    ];

                    // MATRIKS DPH
                    $dph_o[] = [
                        'dph_o' => $total,
                        'dph_o_component' => $component,
                        'dph_o_plan' => $imp->initial_study_plan,
                        'dph_o_rona_awal' => $ronaAwal,
                        'dph_o_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'dph_o_study_location' => $imp->study_location,
                        'dph_o_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'dph_o_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'dph_o_ed_besaran_rencana' => $ed_besaran_rencana,
                        'dph_o_ed_kondisi_rona' => $ed_kondisi_rona,
                        'dph_o_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'dph_o_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'dph_o_ed_kesimpulan' => $ed_kesimpulan,
                        'dph_o_env_impact' => "$change_type $ronaAwal"
                    ];

                    // BATAS WAKTU KAJIAN
                    $bwk_o[] = [
                        'bwk_o' => $total,
                        'bwk_o_component' => $component,
                        'bwk_o_env_impact' => "$change_type $ronaAwal",
                        'bwk_o_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                } else if ($s->name == 'Pasca Operasi') {
                    // MATRIKS DP
                    if (!in_array($component, $dp_po_name)) {
                        $dp_po_name[] = $component;
                        $dp_po[] = [
                            'dp_po' => 'Tahap ' . $s->name . $stage_merge,
                            'dp_po_component' => $component
                        ];
                    }

                    // MATRIKS DPH
                    if (!in_array($component, $mdph_po_name)) {
                        $mdph_po_name[] = $component;
                        $mdph_po[] = [
                            'mdph_po' => 'Tahap ' . $s->name . $stage_merge,
                            'mdph_po_component' => $component
                        ];
                    }

                    // COMPONENT
                    if (!in_array($component, $com_po_name)) {
                        $com_po_name[] = $component;
                        $com_po[] = [
                            'com_po_name' => '2.4.' . count($com_po_name) . ' ' . $component
                        ];
                    }

                    // DESKRIPSI BATAS WILAYAH STUDI
                    if(strtolower($component_type) == 'geofisik kimia' || strtolower($component_type) == 'biologi') {
                        $po_bwk[] = [
                            'po_bwk' => $ronaAwal . ' akibat ' . $component,
                            'po_bwk_ba' => ''
                        ];
                    }

                    $po[] = [
                        'ka_po' => $total,
                        'ka_po_component' => $component,
                        'ka_po_plan' => $imp->initial_study_plan,
                        'ka_po_rona_awal' => $ronaAwal,
                        'ka_po_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'ka_po_study_location' => $imp->study_location,
                        'ka_po_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'ka_po_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'ka_po_ed_besaran_rencana' => $ed_besaran_rencana,
                        'ka_po_ed_kondisi_rona' => $ed_kondisi_rona,
                        'ka_po_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'ka_po_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'ka_po_ed_kesimpulan' => $ed_kesimpulan,
                        'ka_po_env_impact' => "$change_type $ronaAwal"
                    ];

                    // MATRIKS DPH
                    $dph_po[] = [
                        'dph_po' => $total,
                        'dph_po_component' => $component,
                        'dph_po_plan' => $imp->initial_study_plan,
                        'dph_po_rona_awal' => $ronaAwal,
                        'dph_po_hypothetical' => $imp->is_hypothetical_significant ? 'DPH' : 'Tidak DPH',
                        'dph_po_study_location' => $imp->study_location,
                        'dph_po_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                        'dph_po_potential_impact' => "$change_type $ronaAwal akibat $component",
                        'dph_po_ed_besaran_rencana' => $ed_besaran_rencana,
                        'dph_po_ed_kondisi_rona' => $ed_kondisi_rona,
                        'dph_po_ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                        'dph_po_ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                        'dph_po_ed_kesimpulan' => $ed_kesimpulan,
                        'dph_po_env_impact' => "$change_type $ronaAwal"
                    ];

                    // BATAS WAKTU KAJIAN
                    $bwk_po[] = [
                        'bwk_po' => $total,
                        'bwk_po_component' => $component,
                        'bwk_po_env_impact' => "$change_type $ronaAwal",
                        'bwk_po_study_length' => $imp->study_length_year . ' tahun ' . $imp->study_length_month . ' bulan',
                    ];
                }
                $stage_id = $s->id;
                $total++;
            }
        }

        // ============ PROJECT ADDRESS ============ //
        $project_address = '';
        $project_district = '';
        $project_province = '';
        if ($project->address) {
            if ($project->address->first()) {
                $project_address = $project->address->first()->address;
                $project_district = $project->address->first()->district;
                $project_province = $project->address->first()->prov;
            }
        }

        // ========= DESKRIPSI RENCANA USAHA DAN/ATAU KEGIATAN ======== //
        $deskripsi_rencana = [];
        $desk_ren_no = 'A';
        $sub_projects = SubProject::where([['id_project', $id_project],['type', 'utama']])->get();
        foreach($sub_projects as $sp) {
            $sub_project_component = SubProjectComponent::where('id_sub_project', $sp->id)->get();
            $last_ren_no = null;
            foreach($sub_project_component as $spc) {
                $desk_ren_pen = '';
                if($spc->name) {
                    $desk_ren_pen = $spc->name;
                } else if($spc->component) {
                    $desk_ren_pen = $spc->component->name;
                }

                if($last_ren_no == $desk_ren_no) {
                    $ren_no = $desk_ren_no . '<w:vMerge w:val="continue"/>';
                    $ren_ru =  $sp->name . '<w:vMerge w:val="continue"/>';
                } else {
                    $ren_no = $desk_ren_no . '<w:vMerge w:val="restart"/>'; 
                    $ren_ru =  $sp->name . '<w:vMerge w:val="restart"/>';
                }

                $deskripsi_rencana[] = [
                    'deskripsi_rencana' => $ren_no,
                    'deskripsi_rencana_ru' => $ren_ru,
                    'deskripsi_rencana_pendukung' => $desk_ren_pen,
                    'deskripsi_rencana_besaran' => $spc->unit,
                    'deskripsi_rencana_lokasi' => $spc->description_common
                ];
                $last_ren_no = $desk_ren_no;
            }
            $desk_ren_no++;
        }

        // ======= RONA AWAL ============
        $fisika_kima = [];
        $biologi = [];
        $sosekbud = [];
        $kesmas = [];

        $component_type = ComponentType::all();
        foreach($component_type as $ct) {
            if($ct->name == 'Biologi') {
                $rona_awal = RonaAwal::where('id_component_type', $ct->id)->get();
                foreach($rona_awal as $ra) {
                    $biologi[] = [
                        'rona_biologi_name' => $ra->name
                    ];
                }
                $sp_rona = SubProject::where('id_project', $id_project)->get();
                foreach($sp_rona as $sr) {
                    $spr_component = SubProjectComponent::where('id_sub_project', $sr->id)->get();
                    foreach($spr_component as $sprc) {
                        $spr_rona = SubProjectRonaAwal::where([['id_sub_project_component', $sprc->id],['id_component_type', $sr->id]])->get();
                        foreach($spr_rona as $sprr) {
                            $biologi[] = [
                                'rona_biologi_name' => $sprr->name
                            ];
                        }
                    }
                }
            }
        }

        // ======== KONSULTASI PUBLIK ======== //
        $konsul_publik_date = '';
        $konsul_publik_lokasi = '';
        $konsul_publik_peserta = '';
        $konsul_publik_total = '';
        $konsultasi_publik = PublicConsultation::where('project_id', $id_project)->first();
        if($konsultasi_publik) {
            $konsul_publik_date = $konsultasi_publik->event_date ? Carbon::createFromFormat('Y-m-d',  $konsultasi_publik->event_date)->isoFormat('D MMMM Y')  : '' ;
            $konsul_publik_lokasi = $konsultasi_publik->location;
            $konsul_publik_peserta = '';
            $konsul_publik_total = $konsultasi_publik->participant;
        }

        // ======= EVALUASI HOLISTIK ====== //
        $holistic_evaluations = '';
        // $hol_eval = HolisticEvaluation::where('id_project', $id_project)->first();
        // if($hol_eval) {
        //     $holistic_evaluations = $hol_eval->description;
        // }
        $holEvalTable = new Table();
        $holEvalTable->addRow();
        $cell = $holEvalTable->addCell();
        Html::addHtml($cell, $holistic_evaluations);

        $templateProcessor = new TemplateProcessor('template_andal.docx');

        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('project_title_s', strtolower($project->project_title));
        $templateProcessor->setValue('project_title', ucwords(strtolower($project->project_title)));
        $templateProcessor->setValue('district', ucwords(strtolower($project_district)));
        $templateProcessor->setValue('province', ucwords(strtolower($project_province)));
        $templateProcessor->setValue('pemrakarsa_pic', $project->initiator->pic);
        $templateProcessor->setValue('address', ucwords(strtolower($project_address)));
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
        $templateProcessor->setValue('konsul_publik_date', $konsul_publik_date);
        $templateProcessor->setValue('konsul_publik_lokasi', $konsul_publik_lokasi);
        $templateProcessor->setValue('konsul_publik_peserta', $konsul_publik_peserta);
        $templateProcessor->setValue('konsul_publik_total', $konsul_publik_total);
        $templateProcessor->cloneRowAndSetValues('tim_penyusun', $formulator);
        $templateProcessor->cloneRowAndSetValues('tim_ahli', $experts);
        $templateProcessor->cloneRowAndSetValues('ka_pk', $pk);
        $templateProcessor->cloneRowAndSetValues('ka_k', $k);
        $templateProcessor->cloneRowAndSetValues('ka_o', $o);
        $templateProcessor->cloneRowAndSetValues('ka_po', $po);
        $templateProcessor->cloneRowAndSetValues('dp_pk', $dp_pk);
        $templateProcessor->cloneRowAndSetValues('dp_k', $dp_k);
        $templateProcessor->cloneRowAndSetValues('dp_o', $dp_o);
        $templateProcessor->cloneRowAndSetValues('dp_po', $dp_po);
        $templateProcessor->cloneRowAndSetValues('dph_pk', $dph_pk);
        $templateProcessor->cloneRowAndSetValues('dph_k', $dph_k);
        $templateProcessor->cloneRowAndSetValues('dph_o', $dph_o);
        $templateProcessor->cloneRowAndSetValues('dph_po', $dph_po);
        $templateProcessor->cloneRowAndSetValues('mdph_pk', $mdph_pk);
        $templateProcessor->cloneRowAndSetValues('mdph_k', $mdph_k);
        $templateProcessor->cloneRowAndSetValues('mdph_o', $mdph_o);
        $templateProcessor->cloneRowAndSetValues('mdph_po', $mdph_po);
        $templateProcessor->cloneRowAndSetValues('bwk_pk', $bwk_pk);
        $templateProcessor->cloneRowAndSetValues('bwk_k', $bwk_k);
        $templateProcessor->cloneRowAndSetValues('bwk_o', $bwk_o);
        $templateProcessor->cloneRowAndSetValues('bwk_po', $bwk_po);
        $templateProcessor->cloneRowAndSetValues('deskripsi_rencana', $deskripsi_rencana);
        $templateProcessor->cloneRowAndSetValues('pk_bwk', $pk_bwk);
        $templateProcessor->cloneRowAndSetValues('k_bwk', $k_bwk);
        $templateProcessor->cloneRowAndSetValues('o_bwk', $o_bwk);
        $templateProcessor->cloneRowAndSetValues('po_bwk', $po_bwk);
        $templateProcessor->cloneBlock('rona_biologi', count($biologi), true, false, $biologi);
        $templateProcessor->cloneBlock('com_pk_block', count($com_pk), true, false, $com_pk);
        $templateProcessor->cloneBlock('com_k_block', count($com_k), true, false, $com_k);
        $templateProcessor->cloneBlock('com_o_block', count($com_o), true, false, $com_o);
        $templateProcessor->cloneBlock('com_po_block', count($com_po), true, false, $com_po);
        $templateProcessor->setComplexBlock('holistic_evaluation',  $holEvalTable);

        // PRAKIRAAN DAMPAK PENTING
        $pdp = [];
        for($i = 0; $i < count($dpg_pk_block); $i++) {
            $pdp[] = [
                'dpg_pk_component_no' => '6.1.' . $i + 1 . '. ' . $dpg_pk_block[$i]['dpg_pk_component'],
                'dpg_pk_component' => $dpg_pk_block[$i]['dpg_pk_component'],
                'dpg_pk_imp_block' => $dpg_pk_block[$i]['dpg_pk_imp_block'],
                '/dpg_pk_imp_block' => $dpg_pk_block[$i]['/dpg_pk_imp_block'],
            ];
        }
        $templateProcessor->cloneBlock('dpg_pk_block', count($pdp), true, false, $pdp);

        // DAMPAK PADA PRAKIRAAN DAMPAK PENTING
        if(count($dpg_pk_block) > 0) {
            for($i = 0; $i < count($dpg_pk_block); $i++) {
                $dampak = [];
                for($a = 0; $a < count($dpg_pk_block[$i]['dampak']); $a++) {
                    $dampak[$a] = [
                        'dpg_pk_impact_no' => '6.1.' . $i + 1 . '.' . $a + 1 . '. ' . $dpg_pk_block[$i]['dampak'][$a],
                        'dpg_pk_impact' => $dpg_pk_block[$i]['dampak'][$a],
                        'dpg_pk_impact_small' => strtolower($dpg_pk_block[$i]['dampak'][$a]),
                        'dpg_pk_studies' => $dpg_pk_block[$i]['table_with_no_plan'][$a]['studies'],
                        'dpg_pk_no_plan' => $dpg_pk_block[$i]['table_with_no_plan'][$a]['no_plan'],
                        'dpg_pk_with_plan' => $dpg_pk_block[$i]['table_with_no_plan'][$a]['with_plan'],
                        'dpg_pk_size_differ' => $dpg_pk_block[$i]['table_with_no_plan'][$a]['size_differ'],
                        'dpg_pk_imp_result' => $dpg_pk_block[$i]['impact_result'][$a],
                    ];

                    $status = 'tidak penting';

                    for($o = 1; $o < count($dpg_pk_block[$i]['important_trait'][$a]) + 1; $o++) {
                        $dampak[$a]['dpg_pk_it_' . $o] = $dpg_pk_block[$i]['important_trait'][$a][$o - 1]['nilai'];
                        $dampak[$a]['dpg_pk_itk_' . $o] = $dpg_pk_block[$i]['important_trait'][$a][$o - 1]['keterangan'];

                        if($status == 'tidak penting') {
                            if($dampak[$a]['dpg_pk_it_' . $o] == '+P' || $dampak[$a]['dpg_pk_it_' . $o] == '-P') {
                                $status = 'penting';
                            }
                        }
                    }

                    $dampak[$a]['dpg_pk_imp_status'] = $status;
                }
                $no = $i + 1;
                $templateProcessor->cloneBlock('dpg_pk_imp_block_' . $no, count($dampak), true, false, $dampak);
            }
        }

        $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));

        return response()->json(['message' => 'success']);
    }

    private function getComponentType($imp) {
        $component_type = '';
        if($imp->subProjectRonaAwal->id_rona_awal) {
            $com_type = ComponentType::find($imp->subProjectRonaAwal->ronaAwal->id_component_type);
            if($com_type) {
                $component_type = $com_type->name;
            }
        } else {
            if($imp->subProjectRonaAwal->id_component_type) {
                $com_type = ComponentType::find($imp->subProjectRonaAwal->id_component_type);
                if($com_type) {
                    $component_type = $com_type->name;
                }
            }
        }

        return $component_type;
    }

    private function formulirKa($id_project)
    {
        $ids = [4, 1, 2, 3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
            return array_search($model->getKey(), $ids);
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

        if ($formulator && $formulator->member) {
            foreach ($formulator->member as $f) {
                $formulator_data = Formulator::find($f->id_formulator);
                if ($formulator_data) {
                    $results['penyusun'][] = [
                        'name' => $formulator_data->name,
                        'position' => $f->position
                    ];
                }
            }
        }

        $publicConsultation = PublicConsultation::select('id', 'project_id', 'positive_feedback_summary', 'negative_feedback_summary')
            ->where('project_id', $id_project)->get();

        $results['positive'] = [];
        $results['negative'] = [];

        foreach ($publicConsultation as $p) {
            $results['positive'][] = ['val' => $p->positive_feedback_summary ?? ''];
            $results['negative'][] = ['val' => $p->negative_feedback_summary ?? ''];
        }

        $im = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'initial_study_plan', 'is_hypothetical_significant', 'study_location', 'study_length_year', 'study_length_month')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->with('potentialImpactEvaluation.pieParam')->get();

        $total_ms = 0;
        foreach ($stages as $s) {
            $total = 0;
            $results[str_replace(' ', '_', strtolower($s->name))] = [];
            foreach ($im as $pA) {
                $ronaAwal = '';
                $component = '';

                $data = $this->getComponentRonaAwal($pA, $s->id);

                if ($data['component'] && $data['ronaAwal']) {
                    $ronaAwal = $data['ronaAwal'];
                    $component = $data['component'];
                } else {
                    continue;
                }

                $changeType = $pA->id_change_type ? $pA->changeType->name : '';

                // ======= POTENTIAL IMPACT EVALUATIONS ======= //
                $ed_besaran_rencana_title = '';
                $ed_besaran_rencana = '';
                $ed_kondisi_rona_title = '';
                $ed_kondisi_rona = '';
                $ed_pengaruh_rencana_title = '';
                $ed_pengaruh_rencana = '';
                $ed_intensitas_perhatian_title = '';
                $ed_intensitas_perhatian = '';
                $ed_kesimpulan_title = '';
                $ed_kesimpulan = '';

                if ($pA->is_hypothetical_significant && $pA->potentialImpactEvaluation) {
                    foreach ($pA->potentialImpactEvaluation as $po) {
                        if ($po->id_pie_param == 1) {
                            $ed_besaran_rencana_title = $po->pieParam->name;
                            $ed_besaran_rencana = $po->text;
                        } else if ($po->id_pie_param == 2) {
                            $ed_kondisi_rona_title = $po->pieParam->name;
                            $ed_kondisi_rona = $po->text;
                        } else if ($po->id_pie_param == 3) {
                            $ed_pengaruh_rencana_title = $po->pieParam->name;
                            $ed_pengaruh_rencana = $po->text;
                        } else if ($po->id_pie_param == 4) {
                            $ed_intensitas_perhatian_title = $po->pieParam->name;
                            $ed_intensitas_perhatian = $po->text;
                        } else if ($po->id_pie_param == 5) {
                            $ed_kesimpulan_title = $po->pieParam->name;
                            $ed_kesimpulan = $po->text;
                        }
                    }
                }

                $results[str_replace(' ', '_', strtolower($s->name))][] = [
                    'no' => $total + 1,
                    'component_name' => "$changeType $ronaAwal akibat $component",
                    'rencana' => $pA->initial_study_plan ?? '',
                    'rona_lingkungan' => $ronaAwal,
                    'dampak_potensial' => "$changeType $ronaAwal akibat $component",
                    'ed_besaran_rencana_title' => $ed_besaran_rencana_title,
                    'ed_besaran_rencana' => $ed_besaran_rencana,
                    'ed_kondisi_rona_title' => $ed_kondisi_rona_title,
                    'ed_kondisi_rona' => $ed_kondisi_rona,
                    'ed_pengaruh_rencana_title' => $ed_pengaruh_rencana_title,
                    'ed_pengaruh_rencana' => $ed_pengaruh_rencana,
                    'ed_intensitas_perhatian_title' => $ed_intensitas_perhatian_title,
                    'ed_intensitas_perhatian' => $ed_intensitas_perhatian,
                    'ed_kesimpulan_title' => $ed_kesimpulan_title,
                    'ed_kesimpulan' => $ed_kesimpulan,
                    'dph' => $pA->is_hypothetical_significant ? 'DPH' : 'DTPH',
                    'batas_wilayah' => $pA->study_location ?? '',
                    'batas_waktu' => $pA->study_length_year . ' tahun ' . $pA->study_length_month . ' bulan'
                ];

                if ($pA->impactStudy) {
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

    private function getComponentRonaAwal($imp, $id_project_stage)
    {
        $component = null;
        $ronaAwal = null;

        if ($imp->subProjectComponent) {
            if ($imp->subProjectComponent->id_project_stage == $id_project_stage) {
                if ($imp->subProjectRonaAwal) {
                    $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                    $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                }
            } else if ($imp->subProjectComponent->id_project_stage != null) {
                if (($imp->subProjectComponent->component) && $imp->subProjectComponent->component->id_project_stage == $id_project_stage) {
                    if ($imp->subProjectRonaAwal) {
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

    private function exportKAPDF($idProject)
    {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
        $project = Project::findOrFail($idProject);

        //Load word file
        $Content = IOFactory::load(storage_path('app/public/formulir/ka-andal-' . strtolower($project->project_title) . '.docx'));

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $PDFWriter->save(storage_path('app/public/formulir/ka-andal-' . strtolower($project->project_title) . '.pdf'));

        return response()->download(storage_path('app/public/formulir/ka-andal-' . strtolower($project->project_title) . '.pdf'))->deleteFileAfterSend(false);
    }

    private function getComments($id)
    {
        $komen = Comment::where([['id_impact_identification', $id], ['document_type', 'andal'], ['reply_to', null]])
            ->orderBY('id', 'DESC')->get();

        $comments = [];
        foreach ($komen as $c) {
            $replies = [];

            if ($c->reply) {
                foreach ($c->reply as $r) {
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
