<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Or your base user model if it extends that

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'family_member',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'property',
        'unit',
        'purchase_type',
        'email',
        'phone',
        'profile_image',
    ];

    /**
     * Get the user that owns the tenant profile.
     * The foreign key on this table is 'user_id'.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the property associated with the tenant.
     * ✅ RENAMED from property() to linked_property() to avoid conflict
     */
    public function linked_property()
    {
        // The foreign key is the 'property' column in this table.
        return $this->belongsTo(Property::class, 'property');
    }
    public function contracts()
    {
        // This defines the relationship: A Tenant "Has Many" Contracts.
        // It assumes the foreign key in your 'contracts' table is 'tenant_id'.
        return $this->hasMany(Contract::class, 'tenant_id');
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class, 'buyer_id');
    }
}
