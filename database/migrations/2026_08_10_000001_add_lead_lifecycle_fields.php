<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            $table->string('source')->nullable()->after('page');
            $table->string('source_ip', 45)->nullable()->after('source');
            $table->text('user_agent')->nullable()->after('source_ip');
            $table->timestamp('consent_at')->nullable()->after('user_agent');
            $table->string('status', 40)->default('pending')->after('consent_at');
            $table->string('crm_status', 40)->default('pending')->after('status');
            $table->text('crm_response')->nullable()->after('crm_status');
            $table->timestamp('crm_sent_at')->nullable()->after('crm_response');
            $table->text('crm_error')->nullable()->after('crm_sent_at');

            $table->index('status');
            $table->index('crm_status');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropIndex(['crm_status']);
            $table->dropColumn([
                'source',
                'source_ip',
                'user_agent',
                'consent_at',
                'status',
                'crm_status',
                'crm_response',
                'crm_sent_at',
                'crm_error',
            ]);
        });
    }
};
