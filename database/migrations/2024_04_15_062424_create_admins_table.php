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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('admin_type', ['super', 'sub']);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
