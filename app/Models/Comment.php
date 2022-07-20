<?php

namespace App\Models;

use App\Abstracts\CommentPattern;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Comment extends CommentPattern
{
    protected $fillable = [
        'body',
        'email',
        'user_name',
        'id_user',
        'id_post',
    ];

    public static function getPostComments(int $id_post): LengthAwarePaginator
    {
        return Comment::with([
                'user:id,role',
                'replies' => function($q) {
                    $q->select('id', 'body', 'created_at', 'user_name', 'id_reply_to', 'id_user');
                },
                'replies.user' => function($q) {
                    $q->select('id', 'role');
                },
            ])
            ->where('id_post', $id_post)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public static function getUserComments(int $id_user): LengthAwarePaginator
    {
        return DB::table('comments as c')
            ->select('c.body', 'c.created_at', 'c.id', 'c.user_name', 'p.title', 'p.slug')
            ->join('posts as p', 'p.id', '=', 'c.id_post')
            ->where([
                ['c.body', '!=', null],
                ['c.id_user', $id_user],
            ])
            ->orderBy('c.created_at', 'desc')
            ->paginate(10);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'id_reply_to')->orderBy('created_at', 'asc');
    }
}
