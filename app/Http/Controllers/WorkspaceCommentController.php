<?php

namespace App\Http\Controllers;

use App\Entity\WorkspaceComment;
use DB;
use Illuminate\Http\Request;

class WorkspaceCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $comment = [];
        $comment = WorkspaceComment::where([['filename_document', $request->filename_document], ['reply_to', null]])->orderBY('id', 'DESC')->get();
        // $comments = [];
        foreach($comment as $c) {
            $replies = [];
            if($c->reply) {
                foreach($c->reply as $r) {
                    $replies[] = [
                        'id' => $r->id,
                        'name' => $r->user->name,
                        'description' => $r->description,
                    ];
                }
                usort($replies, function($a, $b) {
                    return $a['id'] <=> $b['id'];
                });
            }
            $comments[] = [
                'id' => $c->id,
                'user' => $c->user->name,
                'description' => $c->description,
                'page' => $c->page,
                'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'replies' => $replies
            ];
        }
        return $comments;
        // return $comment;
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
        DB::beginTransaction();
        try {
            // dd($request);
            $workspaceComment = new WorkspaceComment();
            $workspaceComment->id_user = $request->id_user;
            $workspaceComment->page = $request->page;
            $workspaceComment->description = $request->description;
            $workspaceComment->repair_page = $request->repair_page;
            $workspaceComment->document_type = $request->document_type ? null : $request->document_type;
            $workspaceComment->reply_to = $request->reply_to;
            $workspaceComment->filename_document = $request->filename_document;
            $workspaceComment->save();
            DB::commit();
            if($request->reply_to) {
                return [
                    'user' => $workspaceComment->name,
                    'description' => $workspaceComment->description,
                    'id_project' => $workspaceComment->id_project,
                    'document_type' => $workspaceComment->document_type,
                ];
            } else {
                return [
                    'id' => $workspaceComment->id,
                    'user' => $workspaceComment->name,
                    'description' => $workspaceComment->description,
                    'id_project' => $workspaceComment->id_project,
                    'document_type' => $workspaceComment->document_type,
                    'repair_page' => $workspaceComment->repair_page,
                    'reply_to' => $workspaceComment->reply_to,
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
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
