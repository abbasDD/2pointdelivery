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
        Schema::create('user_switches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_user_id');
            $table->unsignedBigInteger('switched_user_id');
            $table->enum('platform', ['web', 'api'])->default('web');
            $table->timestamps();

            $table->foreign('original_user_id')->references('id')->on('users');
            $table->foreign('switched_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_switches');
    }
};
