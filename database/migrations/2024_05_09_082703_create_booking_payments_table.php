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
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->text('transaction_id')->nullable();
            $table->string('base_price')->nullable();
            $table->string('distance')->nullable();
            $table->string('base_distance')->nullable();
            $table->string('extra_distance_price')->nullable();
            $table->string('weight')->nullable();
            $table->string('base_weight')->nullable();
            $table->string('extra_weight_price')->nullable();
            $table->string('total_price')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamp('payment_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
