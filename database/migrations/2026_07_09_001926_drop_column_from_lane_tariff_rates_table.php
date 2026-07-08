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
        Schema::table('lane_tariff_rates', function (Blueprint $table) {
            $table->dropColumn('frt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lane_tariff_rates', function (Blueprint $table) {
            $table->decimal('frt', 12, 2)->default(0);
        });
    }
};
