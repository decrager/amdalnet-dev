<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Entity\WorkspaceComment;
use App\Laravue\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Utils\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\PhpWord;
class WorkspaceCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->download) {
            return $this->download1($request->id_project, $request->document_type);
        }

        if($request->id_user) {
            $comments = WorkspaceComment::where('document_type', $request->document_type)->where('id_project', $request->id_project)->where('id_user', $request->id_user)->get();
        } else {
            $comments = WorkspaceComment::where('document_type', $request->document_type)->where('id_project', $request->id_project)->get();
        }
        return $comments;
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
            $workspaceComment = new WorkspaceComment();
            $workspaceComment->id_user = Auth::user()->id;
            $workspaceComment->id_project = $request->id_project;
            $workspaceComment->page = $request->page;
            $workspaceComment->suggest = $request->suggest;
            $workspaceComment->page_fix = $request->page_fix;
            $workspaceComment->response = $request->response;
            $workspaceComment->document_type = $request->document_type;
            $workspaceComment->save();
            DB::commit();
            return $workspaceComment;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
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
        DB::beginTransaction();
        try {
            $workspaceComment = WorkspaceComment::findOrFail($id);
            $workspaceComment->page = $request->page;
            $workspaceComment->suggest = $request->suggest;
            $workspaceComment->page_fix = $request->page_fix;
            $workspaceComment->response = $request->response;
            $workspaceComment->save();
            DB::commit();
            return $workspaceComment;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $workspaceComment = WorkspaceComment::findOrFail($id);
            $workspaceCommentOld = $workspaceComment;
            $workspaceComment->delete();
            return $workspaceCommentOld;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function download1($id_project, $document_type)
    {
        $document = new TemplateProcessor(public_path('document/template_rekap_komentar.docx'));
        $comments = WorkspaceComment::with('user')->where('document_type', $document_type)->where('id_project', $id_project)->get()->toArray();
        $project = Project::findOrFail($id_project)->first();
        $document->setValue('jenis_doc_form', $document_type);
        $document->setValue('nama_kegiatan', $project->project_title);
        $data = collect($comments)->groupBy('document_type')->map(function ($d) {
            return $d->groupBy('user.name');
        });
        $result = $data->toArray();
        // dd($result);
        $i = 0;
        foreach ($result as $blockComment => $kategory) {
            // dd($kategory);
            $replacements[] = array(
                'kategori_komentar' => $blockComment,
                // 'no_ahli' => '${no_ahli_'.$i.'}',
                // 'nama_tuk_ahli' => '${nama_tuk_ahli_'.$i.'}',
                // 'position_ahli' => '${position_ahli_'.$i.'}',
                // 'saran_pendapat_ahli' => '${saran_pendapat_ahli_'.$i.'}',
                // 'halaman_spt_ahli' => '${halaman_spt_ahli_'.$i.'}',
                // 'perbaikan_pemrakarsa_ahli' => '${perbaikan_pemrakarsa_ahli_'.$i.'}',
                // 'halaman_perbaikan_ahli' => '${halaman_perbaikan_ahli_'.$i.'}',
            );
            $i++;
        }
        $document->cloneBlock('block_comment', count($replacements), true, false, $replacements);
        $i = 0;
        foreach($result as $blockName) {
            $replacements = array();
            foreach($blockName as $key => $name) {
                // dd($name);
                $replacements[] = array(
                    'nama_tuk_ahli' => $key,
                    "position_ahli" => $key,
                    'no_ahli' => '${no_ahli_'.$i.'}',
                    'saran_pendapat_ahli' => '${saran_pendapat_ahli_'.$i.'}',
                    'halaman_spt_ahli' => '${halaman_spt_ahli_'.$i.'}',
                    'perbaikan_pemrakarsa_ahli' => '${perbaikan_pemrakarsa_ahli_'.$i.'}',
                    'halaman_perbaikan_ahli' => '${halaman_perbaikan_ahli_'.$i.'}',
                );
                $i++;
            }
        }
        $document->cloneBlock('block_name', count($replacements), true, false, $replacements);
        $i = 0;
        foreach($result as $groups) {
            // dd($group);
            foreach($groups as $group) {
                // dd($group);
                $values = array();
                // $section = (new PhpWord())->addSection();
                // Html::addHtml($section, $group['suggest'], false, false);
                // $containers = $section->getElements();
                foreach($group as $key => $row) {
                    // dd($row);
                    $section = (new PhpWord())->addSection();
                    Html::addHtml($section, $row['suggest'], false, false);
                    $containers = $section->getElements();
                    // dd($containers, $row['suggest']);
                    $values[] = array(
                        "no_ahli_{$i}" => $key +1,
                        // "saran_pendapat_ahli_{$i}" => strip_tags($row['suggest']),
                        "halaman_spt_ahli_{$i}" => $row['page'],
                        "perbaikan_pemrakarsa_ahli_{$i}" => strip_tags($row['response']),
                        "halaman_perbaikan_ahli_{$i}" => $row['page_fix'],
                    );
                    // $document->setComplexBlock('saran_pendapat_ahli_'.($i), $containers[0]);
                    for($j = 0; $j < count($containers); $j++) {
                        $document->setComplexBlock('saran_pendapat_ahli_'.($j), $containers[$j]);
                    }
                }
                // for($j = 0; $j < count($containers); $j++) {
                //     $document->setComplexBlock('saran_pendapat_ahli_'.($j), $containers[$j]);
                // }
                $document->cloneRowAndSetValues("no_ahli_{$i}", $values);
                $i++;
            }
        }
        $outputWord = storage_path('app/public/' . 'print-rekap-komentar.docx');
        $document->saveAs($outputWord);

        return Response()->download($outputWord);

        return $comments;

    }
}
