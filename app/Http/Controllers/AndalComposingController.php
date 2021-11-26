<?php

namespace App\Http\Controllers;

use App\Entity\AndalComment;
use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentification;
use App\Entity\ImportantTrait;
use App\Entity\Project;
use App\Entity\ProjectStage;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;

class AndalComposingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
            
            $project = Project::where('id', $request->idProject)->whereHas('impactIdentifications', function($query) {
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
        if($request->type == 'impact-comment') {
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
                    'replies' => [
                        'id' => null,
                        'created_at' => null,
                        'description' => null
                ]
            ];
        }

        if($request->type == 'impact-comment-reply') {
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

        $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'is_hypothetical_significant')
        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->get();

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
                if($imp->subProjectComponent->id_project_stage == $s->id || $imp->subProjectComponent->component->id_project_stage == $s->id) {
                    $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                    
                    $ronaAwal =  $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                    $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    // $ronaAwal = '';
                    // $component = '';

                    $komen = Comment::where([['id_impact_identification', $imp->id], ['document_type', 'andal'],['reply_to', null]])
                            ->orderBY('id', 'DESC')->get();
                
                    $comments = [];
                    foreach($komen as $c) {
                        $comments[] = [
                            'id' => $c->id,
                            'id_impact_identification' => $c->id_impact_identification,
                            'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                            'user' => $c->user->name,
                            'is_checked' => $c->is_checked,
                            'description' => $c->description,
                            'column_type' => $c->column_type,
                            'replies' => [
                                'id' => $c->reply ? $c->reply->id : null,
                                'created_at' => $c->reply ? $c->reply->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss') : null,
                                'description' => $c->reply ? $c->reply->description : null
                            ]
                        ];
                    }

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

        $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
                                        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->get();
        $results = [];

        foreach($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' =>  $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach($impactIdentifications as $imp) {
                if($imp->subProjectComponent->id_project_stage == $s->id || $imp->subProjectComponent->component->id_project_stage == $s->id) {
                    $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                    $ronaAwal =  $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                    $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    // $ronaAwal = '';
                    // $component = '';

                    $komen = Comment::where([['id_impact_identification', $imp->id], ['document_type', 'andal'],['reply_to', null]])
                            ->orderBY('id', 'DESC')->get();
                
                    $comments = [];
                    foreach($komen as $c) {
                        $comments[] = [
                            'id' => $c->id,
                            'id_impact_identification' => $c->id_impact_identification,
                            'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                            'user' => $c->user->name,
                            'is_checked' => $c->is_checked,
                            'description' => $c->description,
                            'column_type' => $c->column_type,
                            'replies' => [
                                'id' => $c->reply ? $c->reply->id : null,
                                'created_at' => $c->reply ? $c->reply->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss') : null,
                                'description' => $c->reply ? $c->reply->description : null
                            ]
                        ];
                    }

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
        $project = Project::findOrFail($id_project);

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

        $save_file_name = $id_project .'-andal' . '.docx'; 
            if (!File::exists(storage_path('app/public/workspace/'))) {
                File::makeDirectory(storage_path('app/public/workspace/'));
            }

            if (!File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
                $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));
            }
            

            return response()->json(['message' => 'success']);

    }
}
