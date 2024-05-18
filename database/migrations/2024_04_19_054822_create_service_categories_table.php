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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            // Random generated alpha numeric unique id
            $table->string('uuid')->unique()->index();
            $table->unsignedBigInteger('service_type_id');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_secureship_enabled')->default(false);
            $table->string('base_price')->nullable()->default('0');
            $table->string('extra_distance_price')->nullable()->default('0');
            $table->string('base_distance')->nullable()->default('0');
            $table->string('base_weight')->nullable()->default('0');
            $table->string('extra_weight_price')->nullable()->default('0');
            $table->string('helper_fee')->nullable()->default('0');
            $table->string('volume_enabled')->nullable()->default('0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('service_type_id')->references('id')->on('service_types');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
