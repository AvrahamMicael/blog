<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use App\Providers\Subscribed;
use App\Providers\Unsubscribed;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Count quantity of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(
            Subscriber::countCache()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriberRequest $req)
    {
        $data = $req->all();
        $secret = Subscriber::genSecret();
        $data['token'] = hash('sha512', $secret);

        $subscriber = Subscriber::create($data);
        $subscriber->secret = $secret;
        Subscribed::dispatch($subscriber);

        cache()->increment('subscribers-count');
        return response(Subscriber::countCache(), 201);
    }

    /**
     * Check the correct secret for the id
     *
     * @param  int  $id
     * @param  string  $secret
     * @return \Illuminate\Http\Response
     */
    public function checkIfSubscriberIsCorrect(int $id, string $secret)
    {
        Subscriber::where('token', hash('sha512', $secret))->findOrFail($id);
        return response('', 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, string $secret)
    {
        $subscriber = Subscriber::where('token', hash('sha512', $secret))->findOrFail($id);
        Unsubscribed::dispatch($subscriber);
        $subscriber->delete();

        cache()->decrement('subscribers-count');
        return response('', 204);
    }
}
