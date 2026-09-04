<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\LeadApprovalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeadReviewController extends Controller
{
    public function __construct(private LeadApprovalService $leadApprovalService) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'mark_fetched' => ['nullable', 'boolean'],
            'source' => ['nullable', 'string', 'max:100'],
        ]);

        $leads = Lead::query()
            ->pendingReview()
            ->when($validated['source'] ?? null, fn ($query, $source) => $query->where('source', $source))
            ->orderBy('created_at')
            ->limit($validated['limit'] ?? 50)
            ->get();

        if ((bool) ($validated['mark_fetched'] ?? false)) {
            $leads->each(fn (Lead $lead) => $this->leadApprovalService->markFetched($lead));
        }

        return response()->json(['data' => $leads->fresh()]);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $validated = $request->validate([
            'approval_status' => ['required', 'in:'.implode(',', [Lead::APPROVAL_APPROVED, Lead::APPROVAL_REJECTED])],
            'qualification_status' => ['nullable', 'in:'.implode(',', [Lead::QUALIFICATION_IN_REVIEW, Lead::QUALIFICATION_QUALIFIED, Lead::QUALIFICATION_DISQUALIFIED, Lead::QUALIFICATION_ERROR])],
            'qualification_source' => ['nullable', 'string', 'max:50'],
            'spam_score' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'qualification_notes' => ['nullable', 'string'],
            'qualification_payload' => ['nullable', 'array'],
        ]);

        if ($lead->processed_at !== null) {
            return response()->json(['lead' => $lead, 'already_processed' => true]);
        }

        return response()->json([
            'lead' => $this->leadApprovalService->review($lead, $validated),
            'already_processed' => false,
        ]);
    }

    public function report(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $to = isset($validated['to']) ? Carbon::parse($validated['to'])->endOfDay() : Carbon::now();
        $from = isset($validated['from'])
            ? Carbon::parse($validated['from'])->startOfDay()
            : $to->copy()->subDays(7)->startOfDay();

        $leads = Lead::query()
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('approval_status')
            ->orderBy('created_at')
            ->limit($validated['limit'] ?? 500)
            ->get();

        return response()->json([
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'total' => $leads->count(),
            'grouped' => $leads->groupBy(fn (Lead $lead) => $lead->approval_status ?? 'unknown')
                ->map(fn ($items) => [
                    'count' => $items->count(),
                    'data' => $items->values(),
                ])
                ->sortKeys(),
        ]);
    }
}
