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
        Schema::create('booking_secureships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->text('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            // fromAddress
            $table->string('fromAddress_addr1')->nullable();
            $table->string('fromAddress_countryCode')->nullable();
            $table->string('fromAddress_postalCode')->nullable();
            $table->string('fromAddress_city')->nullable();
            $table->string('fromAddress_taxId')->nullable();
            $table->boolean('fromAddress_residential')->default(true);
            // toAddress
            $table->string('toAddress_addr1')->nullable();
            $table->string('toAddress_countryCode')->nullable();
            $table->string('toAddress_postalCode')->nullable();
            $table->string('toAddress_city')->nullable();
            $table->string('toAddress_taxId')->nullable();
            $table->boolean('toAddress_residential')->default(true);
            $table->string('billableWeight')->nullable();
            $table->string('billableWeightUnit')->nullable();

            // Other Details
            $table->string('shipDateTime')->nullable();
            $table->string('currencyCode')->nullable();
            // Payment Details
            $table->string('carrierCode')->nullable();
            $table->string('selectedSecureshipService')->nullable();
            $table->string('serviceName')->nullable();
            $table->string('useSecureship')->nullable();
            $table->string('rateZone')->nullable();
            $table->string('pickupAvailable')->nullable();
            $table->string('pickupFee')->nullable();
            $table->string('fuelSurcharge')->nullable();
            $table->string('subTotal')->nullable();
            $table->string('taxAmount')->nullable();
            $table->string('2pointCommission')->nullable();
            $table->string('total')->nullable();
            $table->string('regularPrice')->nullable();
            $table->string('grandTotal')->nullable();

            $table->string('payment_status')->default('unpaid');
            $table->timestamp('payment_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_secureships');
    }
};
