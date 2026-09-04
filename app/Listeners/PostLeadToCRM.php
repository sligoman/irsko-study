<?php

namespace App\Listeners;

use App\Events\LeadSubmitted;
use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Throwable;

class PostLeadToCRM implements ShouldQueue
{
    public int $tries = 3;

    public function handle(LeadSubmitted $event): void
    {
        $lead = Lead::findOrFail($event->lead->id);

        if ($lead->approval_status !== Lead::APPROVAL_APPROVED
            || $lead->qualification_status !== Lead::QUALIFICATION_QUALIFIED) {
            return;
        }

        try {
            $response = Http::timeout(config('services.crm.timeout', 10))
                ->acceptJson()
                ->withToken((string) config('services.crm.token'))
                ->withHeaders(array_filter([
                    'Host' => config('services.crm.host'),
                ]))
                ->post(config('services.crm.lead_url'), [
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone ?? '',
                    'message' => $lead->message ?? '',
                    'page' => $lead->page ?? '',
                    'source' => 'irskostudy_cz',
                    'source_lead_id' => (string) $lead->id,
                    'qualification_status' => Lead::QUALIFICATION_QUALIFIED,
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
