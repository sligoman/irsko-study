<?php

namespace App\Listeners;

use App\Events\LeadSubmitted;
use App\Mail\LeadStored;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendLeadStoredNotification implements ShouldQueue
{
    public function handle(LeadSubmitted $event): void
    {
        Mail::to(config('contacts.email'))->send(new LeadStored($event->lead));
    }
}
