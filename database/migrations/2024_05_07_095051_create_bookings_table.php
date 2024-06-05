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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index(); // Random generated alpha numeric unique id
            $table->unsignedBigInteger('client_user_id');
            $table->unsignedBigInteger('helper_user_id')->nullable();
            $table->unsignedBigInteger('helper_user_id2')->nullable();
            $table->unsignedBigInteger('service_type_id');
            $table->unsignedBigInteger('priority_setting_id');
            $table->unsignedBigInteger('service_category_id');
            $table->string('booking_type')->default('delivery');
            $table->text('pickup_address');
            $table->text('dropoff_address');
            $table->string('pickup_latitude')->nullable();
            $table->string('pickup_longitude')->nullable();
            $table->string('dropoff_latitude')->nullable();
            $table->string('dropoff_longitude')->nullable();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('secureship_order_id')->nullable();
            $table->boolean('is_secureship_enabled')->default(false);
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('receiver_email')->nullable();
            $table->text('delivery_note')->nullable();
            $table->string('status')->default('draft');
            $table->string('total_price');
            $table->dateTime('booking_at')->nullable();
            $table->dateTime('pickup_at')->nullable();
            $table->dateTime('dropoff_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('client_user_id')->references('id')->on('users');
            $table->foreign('helper_user_id')->references('id')->on('users');
            $table->foreign('helper_user_id2')->references('id')->on('users');
            $table->foreign('service_type_id')->references('id')->on('service_types');
            $table->foreign('priority_setting_id')->references('id')->on('priority_settings');
            $table->foreign('service_category_id')->references('id')->on('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
