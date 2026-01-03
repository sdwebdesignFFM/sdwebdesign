<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Internal legal proof data (not shown in PDF)
            // accepted_ip already exists
            $table->text('accepted_user_agent')->nullable()->after('accepted_ip');
            $table->json('accepted_documents')->nullable()->after('accepted_user_agent');
            $table->string('document_hash', 64)->nullable()->after('accepted_documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'accepted_user_agent',
                'accepted_documents',
                'document_hash',
            ]);
        });
    }
};
