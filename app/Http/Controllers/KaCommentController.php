<?php

namespace App\Http\Controllers;

use App\Entity\Comment;
use App\Entity\ProjectStage;
use App\Utils\Html;
use App\Utils\TemplateProcessor;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KaCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->download) {
            return $this->download($request->idProject, $request->documentType);
        }

        if($request->recap) {
            $comment_list = [];
            $comments = Comment::where([['document_type', $request->documentType],['reply_to', null],['id_project', $request->idProject]])->get();

            foreach($comments as $c) {
                // stage
                $change_type = '';
                $rona_awal = '';
                $component = '';

                if($c->impactIdentification) {
                    $change_type = $c->impactIdentification->id_change_type ? $c->impactIdentification->changeType->name : '';

                    if($c->impactIdentification->component) {
                        $component = $c->impactIdentification->component->component->name;
                    }

                    if($c->impactIdentification->ronaAwal) {
                        $rona_awal = $c->impactIdentification->ronaAwal->rona_awal->name;
                    }
                } else if($c->impactIdentificationClone) {
                    $change_type = $c->impactIdentificationClone->id_change_type ? $c->impactIdentificationClone->changeType->name : '';

                    if($c->impactIdentificationClone->projectComponent) {
                        $component = $c->impactIdentificationClone->projectComponent->component->name;
                    }

                    if($c->impactIdentificationClone->projectRonaAwal) {
                        $rona_awal = $c->impactIdentificationClone->projectRonaAwal->rona_awal->name;
                    }
                }

                $comment_list[] = [
                    'id' => $c->id,
                    'stage' => $c->stage ? $c->stage->name : '',
                    'column' => $c->column_type,
                    'notes' => $c->description,
                    'tuk' => $c->user->name,
                    'impact' => "$change_type $rona_awal akibat $component"
                ];

            }
            
            return $comment_list;
        }

        $komen = null;

        if($request->commentType == 'pelingkupan' || $request->commentType == 'pelingkupan-andal' || $request->commentType == 'peta-batas-andal' || $request->commentType == 'peta-batas-ka') {
            $komen = Comment::where([['document_type', $request->commentType], ['id_project', $request->idProject], ['reply_to', null]])
            ->orderBY('id', 'DESC')->get();
        } else {
            $im_column = '';

            if($request->routeName == 'FormulirAmdal') {
                $im_column = 'id_impact_identification';
            } else {
                $im_column = 'id_impact_identification_clone';
            }

            $komen = Comment::where([['document_type', $request->commentType], [$im_column, $request->impactIdentification], ['reply_to', null]])
                ->orderBY('id', 'DESC')->get();
        }


        $comments = [];
        foreach ($komen as $c) {
            $replies = [];

            if ($c->reply) {
                foreach ($c->reply as $r) {
                    $replies[] = [
                        'id' => $r->id,
                        'created_at' => $r->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                        'description' => $r->description,
                        'role' => $r->user->roles->first()->name == 'formulator' ? 'Catatan Balasan Penyusun' : 'Catatan Balasan Penguji'
                    ];
                }
                usort($replies, function($a, $b) {
                    return $a['id'] <=> $b['id'];
                });
            }

            $comments[] = [
                'id' => $c->id,
                'id_impact_identification' => 
                        $c->id_impact_identification ? 
                        $c->id_impact_identification : 
                        $c->id_impact_identification_clone,
                'stage' => $c->id_stage ? ProjectStage::findOrFail($c->id_stage)->name : null,
                'id_project' => $c->id_project,
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

        $comment = new Comment();
        $comment->id_user = $request->id_user;
        $comment->description = $request->description;
        $comment->document_type = $request->document_type;
        $comment->is_checked = false;
        $comment->reply_to = $request->type == 'parent-comment' ? null : $request->id_comment;
        $comment->column_type = $request->column_type;
        $comment->id_stage = $request->id_stage;
        $comment->id_project = $request->id_project;

        if($request->routeName == 'FormulirAmdal') {
            $comment->id_impact_identification = $request->id_impact_identification != 0 ? $request->id_impact_identification: null;
        } else if($request->routeName == 'penyusunanAndal' || $request->routeName == 'penyusunanRKLRPL') {
            $comment->id_impact_identification_clone = $request->id_impact_identification != 0 ? $request->id_impact_identification: null;
        }

        $comment->save();

        if($request->type == 'parent-comment') {
            return [
                'id' => $comment->id,
                'id_impact_identification' => 
                    $comment->id_impact_identification ? 
                    $comment->id_impact_identification : 
                    $comment->id_impact_identification_clone,
                'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'user' => $comment->user->name,
                'is_checked' => $comment->is_checked,
                'description' => $comment->description,
                'column_type' => $comment->column_type,
                'id_project' => $comment->id_project,
                'stage' => $comment->id_stage ? ProjectStage::findOrFail($comment->id_stage)->name : null,
                'replies' => []
            ];
        } else if($request->type == 'reply-comment') {
            return [
                'id' => $comment->id,
                'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'description' => $comment->description,
                'role' => Auth::user()->roles->first()->name == 'formulator' ? 'Catatan Balasan Penyusun' : 'Catatan Balasan Penguji'
            ];
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

    private function renderHtmlTable($data, $width = null, $font = null, $font_size = null)
    {
        $table = new Table();
        $table->addRow();
        $cell = null;
        if($width) {
            $cell = $table->addCell($width);
        } else {
            $cell = $table->addCell();
        }
        $selected_font = $font ? $font : 'Bookman Old Style';
        $selected_font_size = $font_size ? $font_size : '9.5';
        $content = '';
        if($data) {
            $content = str_replace('<p>', '<p style="font-family: ' . $selected_font . '; font-size: ' . $selected_font_size . 'px;">', $this->replaceHtmlList($data));
        }
        Html::addHtml($cell, $content);
        return $table;
    }

    private function replaceHtmlList($data)
    {
        if($data) {
            return str_replace('</ul>', '', str_replace('<ul>', '', str_replace('<li>', '', str_replace('</li>', '<br/>', str_replace('</ol>', '', str_replace('<ol>', '' ,$this->removeNestedParagraph($data)))))));
        } else {
            return '';
        }
    }

    private function removeNestedParagraph($data)
    {
        $old_data = $data;
        $new_data = null;

        while(true) {
            $new_data = preg_replace('/(.*<p>)(((?!<\/p>).)*?)(<p>)(.*?)(<\/p>)(.*)/', '\1\2\5\7', $old_data);
            if($new_data == $old_data) {
                break;
            } else {
                $old_data = $new_data;
            }
        }

        return $new_data;
    }

    private function download($id_project, $type)
    {
        if (!Storage::disk('public')->exists('recap')) {
            Storage::disk('public')->makeDirectory('recap');
        }

        $comment_list = [];
        $document_type = [];
        $html = [];

        if($type == 'ka') {
            $document_type = ['pelingkupan', 'dpdph-ka', 'peta-batas-ka', 'metode-studi-ka'];
        } else if($type == 'andal') {
            $document_type = ['peta-batas-andal', 'pelingkupan-andal', 'dpdph-andal', 'metode-studi-andal', 'andal'];
        } else if($type == 'rkl-rpl') {
            $document_type = ['rkl', 'rpl'];
        }

        $comments = Comment::where([['reply_to', null],['id_project', $id_project]])->whereIn('document_type', $document_type)->get();
        

        foreach($comments as $c) {
            // stage
            $change_type = '';
            $rona_awal = '';
            $component = '';

            if($c->impactIdentification) {
                $change_type = $c->impactIdentification->id_change_type ? $c->impactIdentification->changeType->name : '';

                if($c->impactIdentification->component) {
                    $component = $c->impactIdentification->component->component->name;
                }

                if($c->impactIdentification->ronaAwal) {
                    $rona_awal = $c->impactIdentification->ronaAwal->rona_awal->name;
                }
            } else if($c->impactIdentificationClone) {
                $change_type = $c->impactIdentificationClone->id_change_type ? $c->impactIdentificationClone->changeType->name : '';

                if($c->impactIdentificationClone->projectComponent) {
                    $component = $c->impactIdentificationClone->projectComponent->component->name;
                }

                if($c->impactIdentificationClone->projectRonaAwal) {
                    $rona_awal = $c->impactIdentificationClone->projectRonaAwal->rona_awal->name;
                }
            }

            $comment_list[] = [
                'recap' => count($comment_list) + 1 . '.',
                'name' => $c->user->name,
                'category' => $this->documentType($c->document_type),
                'stage' => $c->stage ? $c->stage->name : '',
                'column' => str_replace('&', 'dan', $c->column_type),
                'note' => '${' . count($comment_list) + 1 . '_desc' .  '}',
            ];

            $html[] = ['replace' => '${' . count($comment_list) . '_desc' .  '}', 'data' => $this->renderHtmlTable($c->description, 1200, 'Calibry', '16')];

        }

        $templateProcessor = new TemplateProcessor('template_rekap_komentar.docx');
        $templateProcessor->cloneRowAndSetValues('recap', $comment_list);

        if(count($html) > 0) {
            for($i = 0; $i < count($html); $i++) {
                $templateProcessor->setComplexBlock($html[$i]['replace'], $html[$i]['data']);
            }
        }

        $save_file_name = 'recap/' .  $id_project . '-' . $type . '.docx';
        $templateProcessor->saveAs(Storage::disk('public')->path($save_file_name));
        return response()->download(Storage::disk('public')->path($save_file_name))->deleteFileAfterSend(true);
    }

    private function documentType($type)
    {
        $title = '';
        switch ($type) {
            case 'pelingkupan':
                $title = 'Pelingkupan';
                break;
            case 'dpdph-ka':
                $title = 'Evaluasi Dampak Potensial dan Dampak Penting Hipotetik';
                break;
            case 'peta-batas-ka':
                $title = 'Peta Batas Wilayah Studi dan Peta Pendukung';
                break;
            case 'metode-studi-ka':
                $title = 'Metode Studi';
                break;
            case 'peta-batas-andal':
                $title = 'Peta Batas Wilayah Studi dan Peta Lainnya';
                break;
            case 'pelingkupan-andal':
                $title = 'Pelingkupan';
                break;
            case 'dpdph-andal':
                $title = 'Evaluasi Dampak Potensial dan Dampak Penting Hipotetik';
                break;
            case 'metode-studi-andal':
                $title = 'Metode Studi';
                break;
            case 'andal':
                $title = 'Analisa Dampak Lingkungan';
                break;
            case 'rkl':
                $title = 'Matriks Rencana Pengelolaan Lingkungan Hidup';
                break;
            case 'rpl':
                $title = 'Matriks Rencana Pemantauan Lingkungan Hidup';
                break;
            default:
                break;
        }

        return $title;
    }
}
