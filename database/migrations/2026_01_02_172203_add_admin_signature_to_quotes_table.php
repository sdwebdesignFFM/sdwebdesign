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
            $table->longText('admin_signature_data')->nullable()->after('signature_at');
            $table->string('admin_signature_name')->nullable()->after('admin_signature_data');
            $table->string('admin_signature_position')->nullable()->after('admin_signature_name');
            $table->timestamp('admin_signed_at')->nullable()->after('admin_signature_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'admin_signature_data',
                'admin_signature_name',
                'admin_signature_position',
                'admin_signed_at',
            ]);
        });
    }
};
