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

    public function test_lead_report_requires_authentication(): void
    {
        $this->getJson('/api/internal/leads/report')->assertUnauthorized();
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
            'source' => 'sandbox-test',
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'processed_at' => null,
        ]);
        Lead::factory()->create([
            'source' => 'website',
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'processed_at' => null,
        ]);

        $this->getJson('/api/internal/leads/pending?source=sandbox-test')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $sandboxLead->id);
    }

    public function test_lead_report_groups_leads_by_approval_status(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $pending = Lead::factory()->create([
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'created_at' => now()->subDay(),
        ]);
        $approved = Lead::factory()->create([
            'approval_status' => Lead::APPROVAL_APPROVED,
            'qualification_status' => Lead::QUALIFICATION_QUALIFIED,
            'created_at' => now()->subDay(),
        ]);

        $this->getJson('/api/internal/leads/report')
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonPath('grouped.pending_review.count', 1)
            ->assertJsonPath('grouped.approved.count', 1)
            ->assertJsonPath('grouped.pending_review.data.0.id', $pending->id)
            ->assertJsonPath('grouped.approved.data.0.id', $approved->id);
    }

    public function test_approval_without_qualification_does_not_dispatch_handoff(): void
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
            'qualification_status' => Lead::QUALIFICATION_IN_REVIEW,
        ])->assertOk();

        Event::assertNotDispatched(LeadSubmitted::class);
    }
}
