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
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('gst_rate');
            $table->string('pst_rate');
            $table->string('hst_rate');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // $table->foreign('country_id')->references('id')->on('countries');
            // $table->foreign('state_id')->references('id')->on('states');
            // $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};
