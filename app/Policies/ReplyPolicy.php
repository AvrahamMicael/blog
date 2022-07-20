<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply)
    {
        return $user->role == Role::ADMIN
            || $user->id == $reply->id_user;
    }

    public function delete(User $user, Reply $reply)
    {
        return self::update($user, $reply);
    }
}
