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
        Schema::create('moving_configs', function (Blueprint $table) {
            $table->id();
            $table->string('no_of_room_price')->nullable();
            $table->string('floor_plan_price')->nullable();
            $table->string('floor_access_price')->nullable();
            $table->string('job_details_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moving_configs');
    }
};
