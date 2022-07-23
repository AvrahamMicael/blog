<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reply',
        'id_user',
    ];

    public $timestamps = false;

    public static function countUserNotifications(): array
    {
        return (array) DB::table('notifications as n')
            ->select(DB::raw('count(*) as notifications_qty'))
            ->where('n.id_user', auth()->id())
            ->first();
    }
}
