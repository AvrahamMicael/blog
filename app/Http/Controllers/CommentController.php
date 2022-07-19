<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id_post)
    {
        $comments = Comment::orderBy('created_at', 'desc')->where('id_post', $id_post)->paginate(10);
        return response($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $req)
    {
        $data = $req->all();
        $data['id_user'] = auth('sanctum')->id();
        $data['user_name'] = optional(auth('sanctum')->user())->name ?? $req->user_name;
        $data['email'] = optional(auth('sanctum')->user())->email ?? $req->email;
        $comment = Comment::create($data);
        return response($comment, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $req, Comment $comment)
    {
        $this->authorize('update', $comment);
        $comment->update($req->only('body'));
        return response($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $repliedComments = Comment::where('id_reply_to', $comment->id)->first();
        if($repliedComments)
        {
            $comment->update([
                'body' => null,
            ]);
            return response($comment);
        }
        $comment->delete();
        return response('', 204);
    }
}
