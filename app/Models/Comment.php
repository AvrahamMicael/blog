<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
