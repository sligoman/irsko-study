<?php

namespace Tests\Feature;

use App\Events\LeadSubmitted;
use App\Listeners\PostLeadToCRM;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LeadCrmDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_is_posted_to_shared_irsko_crm(): void
    {
        config(["services.crm.lead_url" => "https://crm.irsko.ie/api/lead"]);

        Http::fake([
            'https://crm.irsko.ie/api/lead' => Http::response(['id' => 'crm-123'], 201),
        ]);

        $lead = Lead::factory()->create([
            'message' => 'Chci studovat v Irsku.',
            'page' => 'https://irskostudy.cz/kontakt',
            'crm_status' => Lead::CRM_PENDING,
        ]);

        app(PostLeadToCRM::class)->handle(new LeadSubmitted($lead));

        Http::assertSent(function (Request $request) use ($lead) {
            return $request->url() === 'https://crm.irsko.ie/api/lead'
                && $request['name'] === $lead->name
                && $request['email'] === $lead->email
                && $request['message'] === $lead->message
                && $request['page'] === $lead->page;
        });

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'crm_status' => Lead::CRM_DELIVERED,
        ]);
    }
}
