<?php

namespace App\Http\Controllers;

use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\ProjectStage;
use Illuminate\Http\Request;

class KaCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $komen = null;

        if($request->commentType == 'pelingkupan' || $request->commentType == 'peta-batas-andal' || $request->commentType == 'peta-batas-ka') {
            $komen = Comment::where([['document_type', $request->commentType], ['id_project', $request->idProject], ['reply_to', null]])
            ->orderBY('id', 'DESC')->get();
        } else {
            $komen = Comment::where([['document_type', $request->commentType], ['id_impact_identification', $request->impactIdentification], ['reply_to', null]])
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
                        'description' => $r->description
                    ];
                }
            }

            $comments[] = [
                'id' => $c->id,
                'id_impact_identification' => $c->id_impact_identification,
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
        $comment->id_impact_identification = $request->id_impact_identification != 0 ? $request->id_impact_identification: null;
        $comment->description = $request->description;
        $comment->document_type = $request->document_type;
        $comment->is_checked = false;
        $comment->reply_to = $request->type == 'parent-comment' ? null : $request->id_comment;
        $comment->column_type = $request->column_type;
        $comment->id_stage = $request->id_stage;
        $comment->id_project = $request->id_project;
        $comment->save();

        if($request->type == 'parent-comment') {
            return [
                'id' => $comment->id,
                'id_impact_identification' => $comment->id_impact_identification,
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
                'description' => $comment->description
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
}
