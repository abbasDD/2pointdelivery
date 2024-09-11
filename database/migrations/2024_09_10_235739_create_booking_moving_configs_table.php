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
        Schema::create('booking_moving_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('booking_moving_id');
            $table->unsignedBigInteger('moving_config_id');
            $table->string('type');
            $table->string('name');
            $table->string('price');
            $table->string('helper_fee');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('booking_moving_id')->references('id')->on('booking_movings');
            $table->foreign('moving_config_id')->references('id')->on('moving_configs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_moving_configs');
    }
};
