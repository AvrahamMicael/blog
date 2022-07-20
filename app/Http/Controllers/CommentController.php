<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Role;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id_post)
    {
        return response(
            Comment::getPostComments($id_post)
        );
    }

    /**
     * Display a listing of user resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userComments()
    {
        return response(
            Comment::getUserComments(auth()->id())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $req)
    {
        $data = Comment::getBasicDataToStore($req);
        if($req->id_reply_to)
        {
            $comment = Reply::create($data);
        }
        else
        {
            $comment = Comment::create($data);
        }
        $comment->user = ['role' => optional(auth('sanctum')->user())->role ?? Role::USER];
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

        $existReplies = $comment->replies()->exists();
        if($existReplies)
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
