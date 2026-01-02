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
            // Billing details (for invoice/contract)
            $table->string('billing_company')->nullable()->after('accepted_ip');
            $table->string('billing_name')->nullable()->after('billing_company');
            $table->string('billing_street')->nullable()->after('billing_name');
            $table->string('billing_zip', 10)->nullable()->after('billing_street');
            $table->string('billing_city')->nullable()->after('billing_zip');
            $table->string('billing_country')->nullable()->default('Deutschland')->after('billing_city');
            $table->string('billing_vat_id', 30)->nullable()->after('billing_country');

            // Digital signature
            $table->text('signature_data')->nullable()->after('billing_vat_id'); // Base64 PNG
            $table->timestamp('signature_at')->nullable()->after('signature_data');

            // For future customer account linking
            $table->foreignId('customer_id')->nullable()->after('signature_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn([
                'billing_company',
                'billing_name',
                'billing_street',
                'billing_zip',
                'billing_city',
                'billing_country',
                'billing_vat_id',
                'signature_data',
                'signature_at',
                'customer_id',
            ]);
        });
    }
};
