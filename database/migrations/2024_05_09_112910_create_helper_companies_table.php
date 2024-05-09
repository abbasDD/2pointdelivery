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
        Schema::create('helper_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('helper_id');
            $table->string('company_logo')->nullable();
            $table->string('company_alias')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('hst_number')->nullable();
            $table->string('email')->nullable();
            $table->string('business_phone')->nullable();
            $table->timestamp('business_phone_verified_at')->nullable();
            $table->string('suite')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('helper_id')->references('id')->on('helpers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helper_companies');
    }
};
