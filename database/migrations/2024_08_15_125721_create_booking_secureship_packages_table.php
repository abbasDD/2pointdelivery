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
        Schema::create('booking_secureship_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_secureship_id')->constrained();
            $table->string('packageType');
            $table->string('userDefinedPackageType')->nullable();
            $table->string('weight')->nullable();
            $table->string('weightUnits')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('dimUnits')->nullable();
            $table->string('value')->nullable();
            $table->string('insurance')->nullable();
            $table->boolean('isAdditionalHandling')->default(true);
            $table->string('signatureOptions')->nullable();
            $table->string('description')->nullable();
            $table->boolean('isDangerousGoods')->default(true);
            $table->boolean('isNonStackable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_secureship_packages');
    }
};
