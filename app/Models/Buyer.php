<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Buyer extends Model
{
    use HasFactory;

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
        'lease_start_date',
        'lease_end_date',
        'payment_type',
        'installment_type',
        'installment_duration',
        'total_amount',
        'installment_amount',
        'parent_id',
    ];

    protected $casts = [
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property');
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function paidInstallments()
    {
        return $this->hasMany(Installment::class)->where('status', 'paid');
    }

    public function unpaidInstallments()
    {
        return $this->hasMany(Installment::class)->where('status', 'unpaid');
    }

    public function overdueInstallments()
    {
        return $this->hasMany(Installment::class)->where('status', 'overdue');
    }

    // Generate installments when buyer is created or updated
    public function generateInstallments()
    {
        if ($this->payment_type !== 'installment' || !$this->installment_type || !$this->installment_duration) {
            return;
        }

        // Clear existing installments
        $this->installments()->delete();

        $startDate = Carbon::parse($this->lease_start_date);
        $installmentAmount = $this->installment_amount;
        $duration = $this->installment_duration;

        for ($i = 0; $i < $duration; $i++) {
            $dueDate = $this->installment_type === 'monthly'
                ? $startDate->copy()->addMonths($i)
                : $startDate->copy()->addYears($i);

            Installment::create([
                'buyer_id' => $this->id,
                'installment_number' => $i + 1,
                'due_date' => $dueDate,
                'amount' => $installmentAmount,
                'status' => 'unpaid'
            ]);
        }
    }

    // Check and update overdue installments
    public function updateOverdueStatus()
    {
        $this->installments()
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    // Get payment progress
    public function getPaymentProgress()
    {
        $total = $this->installments()->count();
        $paid = $this->paidInstallments()->count();

        return [
            'total_installments' => $total,
            'paid_installments' => $paid,
            'remaining_installments' => $total - $paid,
            'progress_percentage' => $total > 0 ? round(($paid / $total) * 100, 2) : 0,
            'total_paid_amount' => $this->paidInstallments()->sum('amount'),
            'remaining_amount' => $this->unpaidInstallments()->sum('amount')
        ];
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($buyer) {
            if ($buyer->payment_type === 'installment') {
                $buyer->generateInstallments();
            }
        });

        static::updated(function ($buyer) {
            if ($buyer->payment_type === 'installment' && $buyer->isDirty(['installment_type', 'installment_duration', 'installment_amount', 'lease_start_date'])) {
                $buyer->generateInstallments();
            }
        });
    }
}