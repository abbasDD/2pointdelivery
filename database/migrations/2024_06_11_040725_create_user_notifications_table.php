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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_user_id')->nullable();
            $table->unsignedBigInteger('receiver_user_id');
            $table->enum('receiver_user_type', ['client', 'helper'])->default('client');
            $table->string('reference_id')->nullable();
            $table->string('type');
            $table->string('title');
            $table->text('content');
            $table->boolean('read')->default(false);
            $table->boolean('deleted')->default(false);
            $table->timestamps();


            $table->foreign('sender_user_id')->references('id')->on('users');
            $table->foreign('receiver_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
