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
        Schema::create('booking_movings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->text('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('service_price')->nullable();
            $table->string('distance_price')->nullable();
            $table->string('floor_assess_price')->nullable();
            $table->string('floor_plan_price')->nullable();
            $table->string('job_details_price')->nullable();
            $table->string('no_of_room_price')->nullable();
            $table->string('priority_price')->nullable();
            $table->string('weight_price')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('tax_price')->nullable();
            $table->string('total_price')->nullable();
            $table->string('helper_fee')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('payment_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->string('start_booking_image')->nullable();
            $table->string('signatureStart')->nullable();
            $table->timestamp('start_booking_at')->nullable();
            $table->timestamp('start_intransit_at')->nullable();
            $table->string('complete_booking_image')->nullable();
            $table->string('signatureCompleted')->nullable();
            $table->timestamp('complete_booking_at')->nullable();
            $table->text('incomplete_reason')->nullable();
            $table->timestamp('incomplete_booking_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_movings');
    }
};
