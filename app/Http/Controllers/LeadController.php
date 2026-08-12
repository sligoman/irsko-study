<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $validated = $request->validated();
        unset($validated['consent']);

        $lead = Lead::create($validated + [
            'source' => $validated['source'] ?? $request->route()?->getName() ?? 'website',
            'source_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_at' => now(),
            'status' => Lead::STATUS_PENDING,
            'crm_status' => Lead::CRM_PENDING,
            'approval_status' => Lead::APPROVAL_PENDING_REVIEW,
            'qualification_status' => Lead::QUALIFICATION_UNPROCESSED,
        ]);

        return response()->json([
            'lead' => $lead,
            'message' => 'Lead stored',
        ], 201);
    }
}
