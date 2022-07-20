<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReplyRequest $req, Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update($req->only('body'));
        return response($reply);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);
        $reply->delete();
        return response('', 204);
    }
}
