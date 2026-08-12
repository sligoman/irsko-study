<?php

namespace Tests\Feature;

use App\Events\LeadSubmitted;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeadReviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_leads_require_sanctum_authentication(): void
    {
        $this->getJson('/api/internal/leads/pending')->assertUnauthorized();
    }

    public function test_n8n_can_fetch_pending_leads_and_mark_them_in_review(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $lead = Lead::factory()->create([
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'qualification_status' => Lead::QUALIFICATION_UNPROCESSED,
            'processed_at' => null,
        ]);

        $this->getJson('/api/internal/leads/pending?mark_fetched=1')
            ->assertOk()
            ->assertJsonPath('data.0.id', $lead->id);

        $lead->refresh();
        $this->assertSame(Lead::QUALIFICATION_IN_REVIEW, $lead->qualification_status);
        $this->assertNotNull($lead->n8n_fetched_at);
    }

    public function test_approved_lead_dispatches_handoff_once_and_rejected_lead_does_not(): void
    {
        Event::fake();
        Sanctum::actingAs(User::factory()->create());
        $lead = Lead::factory()->create([
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'qualification_status' => Lead::QUALIFICATION_IN_REVIEW,
            'processed_at' => null,
        ]);

        $this->patchJson("/api/internal/leads/{$lead->id}/review", [
            'approval_status' => Lead::APPROVAL_APPROVED,
            'qualification_status' => Lead::QUALIFICATION_QUALIFIED,
            'spam_score' => 0.1,
            'qualification_notes' => 'Validated by n8n.',
        ])->assertOk()->assertJsonPath('already_processed', false);

        Event::assertDispatchedTimes(LeadSubmitted::class, 1);

        $this->patchJson("/api/internal/leads/{$lead->id}/review", [
            'approval_status' => Lead::APPROVAL_APPROVED,
            'qualification_status' => Lead::QUALIFICATION_QUALIFIED,
        ])->assertOk()->assertJsonPath('already_processed', true);

        Event::assertDispatchedTimes(LeadSubmitted::class, 1);

        $rejected = Lead::factory()->create([
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'qualification_status' => Lead::QUALIFICATION_IN_REVIEW,
        ]);

        $this->patchJson("/api/internal/leads/{$rejected->id}/review", [
            'approval_status' => Lead::APPROVAL_REJECTED,
            'qualification_status' => Lead::QUALIFICATION_DISQUALIFIED,
            'spam_score' => 99.9,
        ])->assertOk();

        Event::assertDispatchedTimes(LeadSubmitted::class, 1);
        $this->assertNotNull($rejected->fresh()->rejected_at);
    }
    public function test_n8n_can_limit_pending_leads_to_a_source(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $sandboxLead = Lead::factory()->create([
            "source" => "sandbox-test",
            "approval_status" => Lead::APPROVAL_PENDING_REVIEW,
            "processed_at" => null,
        ]);
        Lead::factory()->create([
            "source" => "website",
            "approval_status" => Lead::APPROVAL_PENDING_REVIEW,
            "processed_at" => null,
        ]);

        $this->getJson("/api/internal/leads/pending?source=sandbox-test")
            ->assertOk()
            ->assertJsonCount(1, "data")
            ->assertJsonPath("data.0.id", $sandboxLead->id);
    }
}

