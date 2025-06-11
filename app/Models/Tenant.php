<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'national_id', 'phone', 'email', 'nationality', 'address',
        'gender', 'purchase_type', 'payment_method', 'payment_amount',
        'payment_currency', 'bank_name', 'iban_number', 'property_type',
        'building_name', 'floor_number', 'unit_number', 'profile_image',
    ];
}
