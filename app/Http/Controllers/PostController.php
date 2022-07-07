<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $post = Post::with('body')
            ->orderBy('created_at', 'desc')
            ->paginate();
        $post->getCollection()
            ->transform(fn($i) => $i->adjustBodyImagesPaths());

        return response($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $req)
    {
        abort_if($req->user()->role != Role::ADMIN, 401);

        $post = Post::create($req->all());
        $post->saveBody($req);

        return response($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return response(
            Post::with('body')
                ->findOrFail($id)
                ->adjustBodyImagesPaths()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $req, Post $post)
    {
        abort_if($req->user()->role != Role::ADMIN, 401);

        $post->update($req->all());
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Request $req)
    {
        abort_if($req->user()->role != Role::ADMIN, 401);

        $post->delete();
        return response('', 204);
    }
}
