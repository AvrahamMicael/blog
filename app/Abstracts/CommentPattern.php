<?php

namespace App\Abstracts;

use App\Http\Requests\StoreCommentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class CommentPattern extends Model
{
    use HasFactory;

    protected $hidden = [
        'email'
    ];

    public static function getBasicDataToStore(StoreCommentRequest $req): array
    {
        $data = $req->all();
        $data['id_user'] = auth('sanctum')->id();
        $data['user_name'] = optional(auth('sanctum')->user())->name ?? $req->user_name;
        $data['email'] = optional(auth('sanctum')->user())->email ?? $req->email;

        return $data;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
