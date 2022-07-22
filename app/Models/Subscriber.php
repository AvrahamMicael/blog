<?php

namespace App\Models;

use App\Http\Requests\StoreSubscriberRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
    ];

    public string $unsubscribe_link;

    public $timestamps = false;

    protected static function booted()
    {
        parent::boot();

        static::retrieved(function(Subscriber $subscriber) {
            $subscriber->unsubscribe_link = config('app.url_front_end')."/subscriber/delete/$subscriber->id/$subscriber->token";
        });
    }

    public static function countCacheRemember(): int
    {
        return cache()->remember('subscribers-count', 60*60*24, fn() => Subscriber::count());
    }

    public static function genToken(): string
    {
        $secret = '';
        for($i = 1; $i <= 7; $i++)
        {
            $secret .= Str::uuid();
        }
        return $secret;
    }

    public static function createWithToken(Request $req): Subscriber
    {

        $data = $req->validate((new StoreSubscriberRequest)->rules());
        $data['token'] = Subscriber::genToken();
        return Subscriber::create($data);
    }
}
