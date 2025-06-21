<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'property_id',
        'unit_id',
        'invoice_month',
        'end_date',
        'status',
        'notes',
        'parent_id',
    ];

    public static $status = [
        'open' => 'Open',
        'paid' => 'Paid',
        'partial_paid' => 'Partial Paid',
    ];
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get the property unit associated with the invoice.
     * ✅ ADD THIS FUNCTION
     */
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    public function properties()
    {
        return $this->hasOne('App\Models\Property', 'id', 'property_id');
    }

    /**
     * Get the remaining due amount for this invoice.
     *
     * @return float
     */

    public function units()
    {
        return $this->hasOne('App\Models\PropertyUnit', 'id', 'unit_id');
    }

    public function types()
    {
        return $this->hasMany('App\Models\InvoiceItem', 'invoice_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class); // Or whatever your payment model is named
    }

    /**
     * Get the total amount paid for this invoice.
     *
     * @return float
     */
    public function getInvoicePaidAmount(): float
    {
        // Sum the 'amount' column from all related payments
        return $this->payments->sum('amount');
    }

    /**
     * Get the total amount of the invoice.
     * (You likely already have this or a similar method)
     * @return float
     */
    public function getInvoiceTotalAmount(): float
    {
        // This method would calculate the total amount of the invoice
        // based on its line items or other charges.
        // Example:
        return $this->items->sum('total_price');
        // // Or, if it's stored directly:
        // return (float) $this->total_amount; // Assuming you have a 'total_amount' column
    }

    /**
     * Get the remaining due amount for this invoice.
     *
     * @return float
     */
    public function getInvoiceDueAmount(): float
    {
        return $this->getInvoiceTotalAmount() - $this->getInvoicePaidAmount();
    }

    /**
     * Get the sub-total amount of the invoice (before payments or other deductions).
     * (You likely already have this or a similar method)
     * @return float
     */
    public function getInvoiceSubTotalAmount(): float
    {
        // This would sum up the amounts of the line items that make up the invoice.
        // Example:
        // return $this->types->sum('amount'); // If 'types' is your relationship for invoice line items
        return (float) $this->sub_total; // Assuming you have a 'sub_total' column
    }


    public static function statusChange($invoice_id, $status)
    {
        $invoice = Invoice::find($invoice_id);
        $invoice->status = $status;
        $invoice->save();
        return $invoice;
    }
    public function items()
    {
        // This assumes 'invoice_id' in 'invoice_items' table links to 'id' in 'invoices' table.
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public static function addPayment($data)
    {
        $payment = new InvoicePayment();
        $payment->invoice_id = $data['invoice_id'];
        $payment->transaction_id = $data['transaction_id'];
        $payment->payment_type = $data['payment_type'];
        $payment->amount = $data['amount'];
        $payment->payment_date = date('Y-m-d');
        $payment->receipt = !empty($data['receipt']) ? $data['receipt'] : '';
        $payment->notes = $data['notes'];;
        $payment->parent_id = parentId();
        $payment->save();
        $invoice = Invoice::find($data['invoice_id']);
        if ($invoice->getInvoiceDueAmount() <= 0) {
            $status = 'paid';
        } else {
            $status = 'partial_paid';
        }
        Invoice::statusChange($invoice->id, $status);
    }
}
