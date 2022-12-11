<?php

namespace App\Http\Controllers;

use App\Entity\WorkspaceComment;
use Auth;
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
        $comments = WorkspaceComment::where('document_type', $request->document_type)->where('id_project', $request->id_project)->get();
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
}