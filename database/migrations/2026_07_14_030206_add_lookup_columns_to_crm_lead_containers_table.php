<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_lead_containers', function (Blueprint $table) {
            $table->foreignId('origin_port_id')->nullable()->after('container_type')
                ->constrained('ports', 'port_id')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->after('origin_port_id')
                ->constrained('ports', 'port_id')->nullOnDelete();
            $table->foreignId('container_class_id')->nullable()->after('convan_class')
                ->constrained('container_class')->nullOnDelete();
            $table->foreignId('container_size_id')->nullable()->after('convan_size')
                ->constrained('container_size')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('crm_lead_containers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('origin_port_id');
            $table->dropConstrainedForeignId('destination_port_id');
            $table->dropConstrainedForeignId('container_class_id');
            $table->dropConstrainedForeignId('container_size_id');
        });
    }
};
