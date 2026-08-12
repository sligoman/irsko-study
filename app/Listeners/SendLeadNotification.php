<?php

namespace App\Listeners;

use App\Events\LeadSubmitted;
use App\Mail\LeadReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendLeadNotification implements ShouldQueue
{
    public function handle(LeadSubmitted $event): void
    {
        Mail::to($event->lead->email)->send(new LeadReceivedNotification($event->lead));
    }
}
