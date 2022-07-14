<?php

namespace App\Providers;

use App\Mail\UnsubscribedMail;
use App\Providers\Unsubscribed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailUnsubscribedNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Providers\Unsubscribed  $event
     * @return void
     */
    public function handle(Unsubscribed $event)
    {
        Mail::to($event->subscriber->email)->send(new UnsubscribedMail);
    }
}
