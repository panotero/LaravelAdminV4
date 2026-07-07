<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('charge_types', function (Blueprint $table) {
            // PORT   -> usable in Port Charges (tied to a specific port)
            // GENERAL -> usable in General Charges (applies to every booking, no port/lane)
            $table->string('applicable_to')->default('PORT')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('charge_types', function (Blueprint $table) {
            $table->dropColumn('applicable_to');
        });
    }
};
