<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Models\Lead;
use App\Events\LeadSubmitted;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_can_be_created_via_contact_endpoint()
    {
        Event::fake();

        // Prepare payload from factory
        $payload = Lead::factory()->raw();

        $payload['consent'] = true;
        $response = $this->postJson('/contact', $payload);

        $response->assertStatus(201);

        // Assert in database
        $this->assertDatabaseHas('leads', ['email' => $payload['email'], 'name' => $payload['name']]);

        $this->assertDatabaseHas('leads', [
            'email' => $payload['email'],
            'status' => Lead::STATUS_PENDING,
            'crm_status' => Lead::CRM_PENDING,
        ]);

        Event::assertNotDispatched(LeadSubmitted::class);
        $this->assertDatabaseHas('leads', [
            'email' => $payload['email'],
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'qualification_status' => Lead::QUALIFICATION_UNPROCESSED,
        ]);
    }

    public function test_lead_submissions_are_rate_limited_per_ip(): void
    {
        $payload = Lead::factory()->raw() + ['consent' => true];
        $request = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.40']);

        $request->postJson('/contact', $payload)->assertStatus(201);
        $request->postJson('/contact', $payload + ['email' => 'second@example.com'])->assertStatus(201);
        $request->postJson('/contact', $payload + ['email' => 'third@example.com'])->assertStatus(429);
    }

    public function test_lead_requires_consent(): void
    {
        $response = $this->postJson('/contact', Lead::factory()->raw());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['consent']);
    }
}
