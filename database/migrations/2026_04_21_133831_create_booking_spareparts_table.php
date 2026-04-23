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
        Schema::create('booking_spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sparepart_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('qty')->default(1);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_spareparts');
    }
};
