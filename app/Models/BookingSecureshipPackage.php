<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSecureshipPackage extends Model
{
    use HasFactory;

    protected $table = 'booking_secureship_packages';

    protected $fillable = [
        'booking_secureship_id',
        'packageType',
        'userDefinedPackageType',
        'weight',
        'weightUnits',
        'length',
        'width',
        'height',
        'dimUnits',
        'value',
        'insurance',
        'isAdditionalHandling',
        'signatureOptions',
        'description',
        'isDangerousGoods',
        'isNonStackable',
    ];

    protected $casts = [
        'isDangerousGoods' => 'boolean',
        'isNonStackable' => 'boolean',
    ];

    public $timestamps = true;

    /**
     * Get the secure shipment that owns the package.
     */
    public function bookingSecureship()
    {
        return $this->belongsTo(BookingSecureship::class);
    }

    /**
     * Convert the model instance to an array, excluding timestamps.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Remove the created_at and updated_at columns
        unset($array['created_at'], $array['updated_at']);

        return $array;
    }
}
