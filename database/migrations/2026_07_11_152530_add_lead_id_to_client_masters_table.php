<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('uuid')
                ->constrained('crm_leads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lead_id');
        });
    }
};
