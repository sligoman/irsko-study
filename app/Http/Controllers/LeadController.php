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

        // Send notification email (best-effort)
        try {
            $recipient = config('contacts.email', 'info@irsko.ie');
            Mail::to($recipient)->send(new LeadStored($lead));
        } catch (\Throwable $e) {
            // Log the error but don't fail the request
            Log::error('Failed to send lead email: ' . $e->getMessage());
        }

        // Dispatch LeadSubmitted event
        try {
            LeadSubmitted::dispatch($lead);
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch LeadSubmitted event: ' . $e->getMessage());
        }

        return response()->json($lead, 201);
    }
}
