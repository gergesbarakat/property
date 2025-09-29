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
        'unit_size',
        'floor',
        'building',
        'location',
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

    public function property()
    {
        // A Unit "Belongs To" a Property.
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function tenants()
    {
        // Using a proper relationship is better for performance.
        return $this->hasMany(Tenant::class, 'unit');
    }
}
