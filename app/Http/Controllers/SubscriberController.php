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
            Subscriber::countCacheRemember()
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
        $data['token'] = Subscriber::genToken();

        $subscriber = Subscriber::create($data);
        Subscribed::dispatch($subscriber);

        cache()->increment('subscribers-count');
        return response(Subscriber::countCacheRemember(), 201);
    }

    /**
     * Check the correct secret for the id
     *
     * @param  int  $id
     * @param  string  $secret
     * @return \Illuminate\Http\Response
     */
    public function checkIfSubscriberIsCorrect(int $id, string $token)
    {
        Subscriber::where('token', $token)->findOrFail($id);
        return response('', 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, string $token)
    {
        $subscriber = Subscriber::where('token', $token)->findOrFail($id);
        Unsubscribed::dispatch($subscriber);
        $subscriber->delete();

        cache()->decrement('subscribers-count');
        return response('', 204);
    }
}
