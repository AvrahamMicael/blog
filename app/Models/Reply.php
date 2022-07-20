<?php

namespace App\Models;

use App\Abstracts\CommentPattern;

class Reply extends CommentPattern
{
    protected $fillable = [
        'body',
        'email',
        'user_name',
        'id_user',
        'id_reply_to',
        'id_post',
    ];
}
