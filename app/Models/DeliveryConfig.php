<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryConfig extends Model
{
    use HasFactory;

    // Define the table name, in case it is not the plural form of the model name
    protected $table = 'delivery_configs';

    // Specify which attributes are mass assignable
    protected $fillable = ['key', 'value', 'description'];
}
