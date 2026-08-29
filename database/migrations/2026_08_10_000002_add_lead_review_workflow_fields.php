<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            $table->string('approval_status', 50)->default('pending_review')->after('status');
            $table->string('qualification_status', 50)->default('unprocessed')->after('approval_status');
            $table->string('qualification_source', 50)->nullable()->after('qualification_status');
            $table->decimal('spam_score', 5, 2)->nullable()->after('qualification_source');
            $table->text('qualification_notes')->nullable()->after('spam_score');
            $table->text('qualification_payload')->nullable()->after('qualification_notes');
            $table->timestamp('reviewed_at')->nullable()->after('qualification_payload');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->timestamp('processed_at')->nullable()->after('rejected_at');
            $table->timestamp('n8n_fetched_at')->nullable()->after('processed_at');
            $table->timestamp('n8n_processed_at')->nullable()->after('n8n_fetched_at');

            $table->index('approval_status');
            $table->index('qualification_status');
            $table->index(['approval_status', 'processed_at', 'created_at'], 'leads_review_queue_idx');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            $table->dropIndex('leads_review_queue_idx');
            $table->dropIndex(['approval_status']);
            $table->dropIndex(['qualification_status']);
            $table->dropColumn([
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
            ]);
        });
    }
};
