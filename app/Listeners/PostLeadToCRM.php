<?php

namespace App\Listeners;

use App\Events\LeadSubmitted;
use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class PostLeadToCRM implements ShouldQueue
{
    public int $tries = 3;

    public function handle(LeadSubmitted $event): void
    {
        $lead = $event->lead;

        try {
            /** @var Response $response */
            $response = Http::timeout(config('services.crm.timeout', 10))
                ->acceptJson()
                ->post(config('services.crm.lead_url'), [
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone ?? '',
                    'message' => $lead->message ?? '',
                    'page' => $lead->page ?? '',
                ]);

            $response->throw();

            $lead->forceFill([
                'crm_status' => Lead::CRM_DELIVERED,
                'crm_response' => $response->json(),
                'crm_sent_at' => now(),
                'crm_error' => null,
            ])->save();
        } catch (Throwable $exception) {
            $lead->forceFill([
                'crm_status' => Lead::CRM_FAILED,
                'crm_error' => $exception->getMessage(),
            ])->save();

            throw $exception;
        }
    }
}
