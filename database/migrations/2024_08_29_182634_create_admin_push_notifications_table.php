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
        Schema::create('admin_push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->default('all');
            $table->string('title');
            $table->mediumText('body');
            $table->boolean('is_emailed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_push_notifications');
    }
};
