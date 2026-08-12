<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const APPROVAL_PENDING_REVIEW = 'pending_review';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';
    public const QUALIFICATION_UNPROCESSED = 'unprocessed';
    public const QUALIFICATION_IN_REVIEW = 'in_review';
    public const QUALIFICATION_QUALIFIED = 'qualified';
    public const QUALIFICATION_DISQUALIFIED = 'disqualified';
    public const QUALIFICATION_ERROR = 'error';
    public const CRM_PENDING = 'pending';
    public const CRM_DELIVERED = 'delivered';
    public const CRM_FAILED = 'failed';

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'page',
        'source',
        'source_ip',
        'user_agent',
        'consent_at',
        'status',
        'crm_status',
        'crm_response',
        'crm_sent_at',
        'crm_error',
        'approval_status',
        'qualification_status',
        'qualification_source',
        'spam_score',
        'qualification_notes',
        'qualification_payload',
        'reviewed_at',
        'approved_at',
        'rejected_at',
        'processed_at',
        'n8n_fetched_at',
        'n8n_processed_at',
    ];

    protected function casts(): array
    {
        return [
            'consent_at' => 'datetime',
            'crm_response' => 'array',
            'crm_sent_at' => 'datetime',
            'qualification_payload' => 'array',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'processed_at' => 'datetime',
            'n8n_fetched_at' => 'datetime',
            'n8n_processed_at' => 'datetime',
        ];
    }

    public function scopePendingReview($query)
    {
        return $query->where('approval_status', self::APPROVAL_PENDING_REVIEW)
            ->whereNull('processed_at');
    }
}
