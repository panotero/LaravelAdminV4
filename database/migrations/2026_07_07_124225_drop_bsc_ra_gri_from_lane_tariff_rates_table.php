<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lane_tariff_rates', function (Blueprint $table) {
            $table->dropColumn([
                'bsc',
                'ra',
                'gri',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('lane_tariff_rates', function (Blueprint $table) {
            $table->decimal('bsc', 12, 2)->default(0);
            $table->decimal('ra', 12, 2)->default(0);
            $table->decimal('gri', 12, 2)->default(0);
        });
    }
};
