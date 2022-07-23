<?php

namespace App\Models;

use App\Abstracts\CommentPattern;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    private function getRepliedUserIdIfExists(): ?int
    {
        return DB::table('replies as r')
            ->select('u.id')
            ->join('comments as c', 'c.id', '=', 'r.id_reply_to')
            ->leftJoin('users as u', 'u.id', '=', 'c.id_user')
            ->where('r.id', $this->id)
            ->first()
            ?->id;
    }

    public static function getUserReplies(): LengthAwarePaginator
    {
        return DB::table('replies as r')
            ->select('u.role', 'r.body', 'r.id_reply_to', 'r.created_at', 'r.id', 'r.user_name', 'p.title', 'p.slug')
            ->join('posts as p', 'p.id', '=', 'r.id_post')
            ->join('comments as c', 'c.id', '=', 'r.id_reply_to')
            ->leftJoin('users as u', 'u.id', '=', 'r.id_user')
            ->where('c.id_user', auth()->id())
            ->where(function($q) {
                $q->where('r.id_user', '!=', auth()->id())
                    ->orWhere('r.id_user', null);
            })
            ->orderBy('r.created_at', 'desc')
            ->paginate(10);
    }

    public static function createWithNotificationIfUserExists(array $data): Reply
    {
        $reply = Reply::create($data);

        $id_replied_user = $reply->getRepliedUserIdIfExists();
        if($id_replied_user && $id_replied_user != auth('sanctum')->id())
        {
            Notification::create([
                'id_reply' => $reply->id,
                'id_user' => $id_replied_user,
            ]);
        }

        return $reply;
    }
}
