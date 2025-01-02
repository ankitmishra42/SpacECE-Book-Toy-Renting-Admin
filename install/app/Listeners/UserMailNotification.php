<?php

namespace App\Listeners;

use App\Events\UserMailEvent;
use App\Mail\mailSend;
use Illuminate\Support\Facades\Mail;

class UserMailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(UserMailEvent $event)
    {
        Mail::to($event->user->email)->send(new mailSend($event->user, $event->otp));
    }
}
