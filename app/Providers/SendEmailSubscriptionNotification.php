<?php

namespace App\Providers;

use App\Mail\SubscribedMail;
use App\Providers\Subscribed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailSubscriptionNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Providers\Subscribed  $event
     * @return void
     */
    public function handle(Subscribed $event)
    {
        Mail::to($event->subscriber->email)->send(new SubscribedMail($event->subscriber));
    }
}
