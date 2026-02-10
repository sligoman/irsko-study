<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Mail\LeadStored;
use Illuminate\Http\Request;
use App\Events\LeadSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Store a newly created lead.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'nullable|string',
            'page' => 'nullable|string',
        ]);

        // Create lead (make sure Lead model has fillable set for these fields)
        $lead = Lead::create($validated);

        Log::channel('leads')->info('Lead received', [
            'lead_id' => $lead->id,
            'email' => $lead->email,
            'page' => $lead->page,
        ]);

        // Send notification email (best-effort)
        try {
            $recipient = config('contacts.email', 'study@irsko.ie');
            Mail::to($recipient)->send(new LeadStored($lead));
            Log::channel('leads')->info('Lead notification email sent', [
                'lead_id' => $lead->id,
                'recipient' => $recipient,
            ]);
        } catch (\Throwable $e) {
            // Log the error but don't fail the request
            Log::channel('leads')->error('Failed to send lead email', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Dispatch LeadSubmitted event
        try {
            LeadSubmitted::dispatch($lead);
        } catch (\Throwable $e) {
            Log::channel('leads')->warning('Failed to dispatch LeadSubmitted event', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json($lead, 201);
    }
}
