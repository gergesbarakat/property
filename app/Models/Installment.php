<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'installment_number',
        'due_date',
        'amount',
        'status',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeDueThisMonth($query)
    {
        return $query->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year);
    }

    // Methods
    public function markAsPaid($paidDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => $paidDate ?? now()
        ]);
    }

    public function markAsUnpaid()
    {
        $this->update([
            'status' => 'unpaid',
            'paid_date' => null
        ]);
    }

    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date < now();
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'paid' || $this->due_date >= now()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }

    // Automatically update overdue status
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($installment) {
            if ($installment->isOverdue() && $installment->status === 'unpaid') {
                $installment->update(['status' => 'overdue']);
            }
        });
    }
}
