<?php

namespace App\Services;

use App\Events\LeadSubmitted;
use App\Models\Lead;
use Illuminate\Support\Carbon;

class LeadApprovalService
{
    public function markFetched(Lead $lead): void
    {
        if ($lead->n8n_fetched_at !== null) {
            return;
        }

        $lead->forceFill([
            'n8n_fetched_at' => Carbon::now(),
            'qualification_status' => $lead->qualification_status === Lead::QUALIFICATION_UNPROCESSED
                ? Lead::QUALIFICATION_IN_REVIEW
                : $lead->qualification_status,
        ])->save();
    }

    public function review(Lead $lead, array $attributes): Lead
    {
        $now = Carbon::now();
        $wasApproved = $lead->approval_status === Lead::APPROVAL_APPROVED;
        $approvalStatus = $attributes['approval_status'];

        $lead->forceFill([
            'approval_status' => $approvalStatus,
            'qualification_status' => $attributes['qualification_status'] ?? $lead->qualification_status,
            'qualification_source' => $attributes['qualification_source'] ?? 'n8n',
            'spam_score' => $attributes['spam_score'] ?? $lead->spam_score,
            'qualification_notes' => $attributes['qualification_notes'] ?? $lead->qualification_notes,
            'qualification_payload' => array_key_exists('qualification_payload', $attributes)
                ? $attributes['qualification_payload']
                : $lead->qualification_payload,
            'reviewed_at' => $lead->reviewed_at ?? $now,
            'processed_at' => $lead->processed_at ?? $now,
            'n8n_processed_at' => $now,
            'approved_at' => $approvalStatus === Lead::APPROVAL_APPROVED ? ($lead->approved_at ?? $now) : null,
            'rejected_at' => $approvalStatus === Lead::APPROVAL_REJECTED ? ($lead->rejected_at ?? $now) : null,
        ])->save();

        if (! $wasApproved
            && $lead->approval_status === Lead::APPROVAL_APPROVED
            && $lead->qualification_status === Lead::QUALIFICATION_QUALIFIED) {
            LeadSubmitted::dispatch($lead);
        }

        return $lead->fresh();
    }
}
