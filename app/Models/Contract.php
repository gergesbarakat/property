<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'contract_file',
    ];


    public function contracts()
    {
        return $this->hasMany(Contract::class, 'tenant_id');
    }
}
