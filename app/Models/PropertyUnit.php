<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_units';

    protected $fillable = [
        'name',
        'bedroom',
        'property_id',
        'baths',
        'kitchen',
        'status',

        'rent',
        'deposit_amount',
        'deposit_type',
        'late_fee_type',
        'late_fee_amount',
        'incident_receipt_amount',
        'rent_type',
        'rent_duration',
        'start_date',
        'end_date',
        'payment_due_date',
        'parent_id',
        'notes',
    ];

    public static $Types = [
        'fixed' => 'Fixed',
        'percentage' => 'Percentage',
    ];
    public static $rentTypes = [
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'custom' => 'Custom',
    ];
    public function properties()
    {
        // This defines the relationship: A Unit "Belongs To" a Property.
        // It assumes the foreign key in your 'property_units' table is 'property_id'.
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function property()
    {
        // This defines the relationship: A Unit "Belongs To" a Property.
        // It assumes the foreign key in your 'property_units' table is 'property_id'.
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function tenants()
    {
        return Tenant::where('unit', $this->id)->first();
    }
}
