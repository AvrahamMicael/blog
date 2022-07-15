<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
    ];

    protected $hidden = ['token'];

    public $timestamps = false;

    public static function countCacheRemember(): int
    {
        return cache()->remember('subscribers-count', 60*60*24, fn() => Subscriber::count());
    }

    public static function genSecret(): String
    {
        $secret = '';
        for($i = 1; $i <= 7; $i++)
        {
            $secret .= Str::uuid();
        }
        return $secret;
    }
}
