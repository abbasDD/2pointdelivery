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
        Schema::create('helper_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('helper_id')->nullable();
            $table->enum('payment_method', ['paypal', 'stripe']);
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('helper_id')->references('id')->on('helpers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helper_bank_accounts');
    }
};
