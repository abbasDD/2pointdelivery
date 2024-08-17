<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSecureship extends Model
{
    use HasFactory;

    protected $table = 'booking_secureships';

    protected $fillable = [
        'booking_id',
        'transaction_id',
        'payment_method',
        'fromAddress_addr1',
        'fromAddress_countryCode',
        'fromAddress_postalCode',
        'fromAddress_city',
        'fromAddress_taxId',
        'fromAddress_residential',
        'toAddress_addr1',
        'toAddress_countryCode',
        'toAddress_postalCode',
        'toAddress_city',
        'toAddress_taxId',
        'toAddress_residential',
        'billableWeight',
        'billableWeightUnit',
        'shipDateTime',
        'currencyCode',
        'carrierCode',
        'selectedSecureshipService',
        'serviceName',
        'useSecureship',
        'rateZone',
        'pickupAvailable',
        'pickupFee',
        'fuelSurcharge',
        'subTotal',
        'taxAmount',
        '2pointCommission',
        'total',
        'regularPrice',
        'grandTotal',
        'payment_status',
        'payment_at',
    ];

    protected $casts = [
        'fromAddress_residential' => 'boolean',
        'toAddress_residential' => 'boolean',
        'payment_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Get the booking that owns the secure shipment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the package that owns the secure shipment.
     */

    public function packages()
    {
        return $this->hasMany(BookingSecureshipPackage::class);
    }
}
