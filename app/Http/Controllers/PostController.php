<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Role;
use App\Providers\NewPost;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $posts = Post::homeCacheRemember();

            if(!$posts->first()) throw new Exception('Posts not found!');

            $posts->getCollection()
                ->transform(fn($i) => $i->adjustBodyImagesPaths());
        }
        catch(Exception $e)
        {
            return response($e->getMessage(), 500);
        }

        return response($posts);
    }

    public function search(string $search)
    {
        $posts = Post::search($search);

        abort_if(!$posts->first(), 404, 'Posts not found');
        
        $posts->getCollection()
            ->transform(fn($i) => $i->adjustBodyImagesPaths());

        return response($posts);
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
        Post::homeCacheForget();

        NewPost::dispatch($post);

        return response($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $post = cache()->remember("post-$slug", 60*60*24, fn() => 
            Post::with('body')
                ->where('slug', $slug)
                ->first()
        );
        abort_if(!$post, 404);
        return response($post->adjustBodyImagesPaths());
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

        $post->updateBody($req)->update($req->all());
        $post->body;
        $post->adjustBodyImagesPaths();
        cache()->forget("post-$post->slug");
        Post::homeCacheForget();
        return response($post);
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

        cache()->forget("post-$post->slug");
        $post->deleteImages()->delete();
        Post::homeCacheForget();
        return response('', 204);
    }
}
