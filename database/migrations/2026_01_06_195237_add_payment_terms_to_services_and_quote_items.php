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
        Schema::table('services', function (Blueprint $table) {
            $table->text('payment_terms')->nullable()->after('detailed_terms');
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->text('payment_terms')->nullable()->after('detailed_terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('payment_terms');
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn('payment_terms');
        });
    }
};
