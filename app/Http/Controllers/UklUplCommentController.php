<?php

namespace App\Http\Controllers;

use App\Entity\UklUplComment;
use App\Http\Resources\UklUplResource;
use Illuminate\Http\Request;

class UklUplCommentController extends Controller
{
    public function index($id)
    {
        return UklUplResource::collection(UklUplComment::with('user')->where('id_project', '=', $id)->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'comment' => 'required',
                'id_project' => 'filled',
                'id_user' => 'filled',
            ]);
            $comment = UklUplComment::create($request->all());
            if ($comment)
                return ["status" => "true", "commentId" => $comment->id];
        } catch (\Throwable $th) {
            return ["status" => "false", "error" => $th];
        }
    }
}
