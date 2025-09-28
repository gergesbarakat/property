<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'invoice_type',
        'amount',
        'description',
    ];


        public function invoice()
    {
        // This defines that an InvoiceItem "Belongs To" one Invoice.
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


}
