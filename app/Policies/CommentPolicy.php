<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        return $user->role == Role::ADMIN
            || $user->id == $comment->id_user;
    }

    public function delete(User $user, Comment $comment)
    {
        return self::update($user, $comment);
    }
}
