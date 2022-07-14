<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
    ];

    public $timestamps = false;

    public static function countCache()
    {
        return cache()->remember('subscribers-count', 60*60*24, fn() => Subscriber::count());
    }
}
