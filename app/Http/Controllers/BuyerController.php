<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Installment;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_type' => 'required|in:full,installment',
            'installment_type' => 'required_if:payment_type,installment|in:monthly,yearly',
            'installment_duration' => 'required_if:payment_type,installment|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'installment_amount' => 'required_if:payment_type,installment|numeric|min:0',
            'lease_start_date' => 'required|date',
        ]);

        $buyer = Buyer::create($request->all());

        // Installments are automatically generated via model events

        return redirect()->route('buyers.show', $buyer->id)
            ->with('success', 'Buyer created successfully!');
    }

    public function show(Buyer $buyer)
    {
        $buyer->load(['user', 'property', 'unit', 'installments' => function ($query) {
            $query->orderBy('due_date');
        }]);

        // Update overdue status
        $buyer->updateOverdueStatus();

        $paymentProgress = $buyer->getPaymentProgress();

        return view('buyers.show', compact('buyer', 'paymentProgress'));
    }

    public function installments(Buyer $buyer)
    {
        $installments = $buyer->installments()
            ->orderBy('due_date')
            ->paginate(12);

        $stats = [
            'total' => $buyer->installments()->count(),
            'paid' => $buyer->paidInstallments()->count(),
            'unpaid' => $buyer->unpaidInstallments()->count(),
            'overdue' => $buyer->overdueInstallments()->count(),
            'due_this_month' => $buyer->installments()->dueThisMonth()->unpaid()->count()
        ];

        return view('buyers.installments', compact('buyer', 'installments', 'stats'));
    }

    public function payInstallment(Request $request, Installment $installment)
    {
        $request->validate([
            'paid_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $installment->markAsPaid($request->paid_date);

        if ($request->notes) {
            $installment->update(['notes' => $request->notes]);
        }

        return back()->with('success', 'Installment marked as paid!');
    }

    public function dashboard()
    {
        $totalBuyers = Buyer::count();
        $installmentBuyers = Buyer::where('payment_type', 'installment')->count();

        $overdueInstallments = Installment::overdue()->count();
        $dueThisMonth = Installment::dueThisMonth()->unpaid()->count();
        $totalRevenue = Installment::paid()->sum('amount');
        $pendingRevenue = Installment::unpaid()->sum('amount');

        return view('buyers.dashboard', compact(
            'totalBuyers',
            'installmentBuyers',
            'overdueInstallments',
            'dueThisMonth',
            'totalRevenue',
            'pendingRevenue'
        ));
    }
}
