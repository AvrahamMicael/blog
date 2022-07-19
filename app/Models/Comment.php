<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'email',
        'user_name',
        'id_user',
        'id_reply_to',
        'id_post',
    ];

    protected $hidden = [
        'email'
    ];

    public static function getPostComments(int $id_post): LengthAwarePaginator
    {
        return DB::table('comments as c')
            ->select('c.*', 'u.role as user_role')
            ->join('users as u', 'u.id', '=', 'c.id_user')
            ->where('c.id_post', $id_post)
            ->orderBy('c.created_at', 'desc')
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
