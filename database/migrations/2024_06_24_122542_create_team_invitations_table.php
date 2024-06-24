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
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inviter_id');
            $table->unsignedBigInteger('invitee_id')->nullable();
            $table->string('invitee_email')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('inviter_id')->references('id')->on('users');
            $table->foreign('invitee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};
