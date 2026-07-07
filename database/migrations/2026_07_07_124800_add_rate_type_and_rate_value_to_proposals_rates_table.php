<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals_rates', function (Blueprint $table) {
            $table->enum('rate_type', ['fixed', 'percentage'])
                ->default('fixed')
                ->after('proposed_rate');
        });
    }

    public function down(): void
    {
        Schema::table('proposals_rates', function (Blueprint $table) {
            $table->dropColumn([
                'rate_type',
            ]);
        });
    }
};
