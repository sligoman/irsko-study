<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Models\Lead;
use App\Mail\LeadStored;
use App\Events\LeadSubmitted;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_can_be_created_via_contact_endpoint()
    {
        Mail::fake();
        Event::fake();

        // Prepare payload from factory
        $payload = Lead::factory()->raw();

        $response = $this->postJson('/contact', $payload);

        $response->assertStatus(201);

        // Assert in database
        $this->assertDatabaseHas('leads', ['email' => $payload['email'], 'name' => $payload['name']]);

        // Assert mail was sent
        Mail::assertSent(LeadStored::class, function ($mail) use ($payload) {
            return $mail->lead->email === $payload['email'];
        });

        // Assert event dispatched
        Event::assertDispatched(LeadSubmitted::class);
    }
}
