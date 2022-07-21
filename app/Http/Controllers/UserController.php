<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserRequest $req
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $req)
    {
        auth()->user()->update($req->except('email'));
        return response(auth()->user());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        abort_if(auth()->user()->role == Role::ADMIN, 403, 'Author cannot be deleted.');
        auth()->user()->delete();
        return response('', 204);
    }

}
