<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_charges', function (Blueprint $table) {
            $table->id('general_charge_id');

            $table->foreignId('charge_type_id')
                ->constrained('charge_types', 'charge_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('amount', 12, 2)->default(0);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['charge_type_id', 'effective_date']);
            $table->index(['charge_type_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_charges');
    }
};
