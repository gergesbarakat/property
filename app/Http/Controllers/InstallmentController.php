<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem; // ✅ ADD THIS LINE

use App\Models\Installment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstallmentController extends Controller
{
    /**
     * Update the status of an installment and create an invoice with its item.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'installment_id' => 'required|exists:installments,id',
            'payment_type' => 'required|in:full,partial',
            'amount' => 'required|numeric|min:0.01',
            'partial_amount' => 'required_if:payment_type,partial|numeric|min:0.01',
            'payment_date' => 'required|date',
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        DB::beginTransaction();
        try {
            $originalInstallment = Installment::with('buyer')->findOrFail($request->installment_id);
            $tenant = $originalInstallment->buyer;

            if (!$tenant) {
                throw new \Exception('The buyer for this installment could not be found.');
            }

            if ($originalInstallment->status === 'paid') {
                return redirect()->back()->with('error', 'This installment has already been paid.');
            }

            $receiptPath = $request->file('receipt')->store('receipts', 'public');

            if ($request->payment_type === 'full') {
                // --- FULL PAYMENT LOGIC ---
                $this->handlePayment($originalInstallment, $tenant, $request->amount, $request->payment_date, $receiptPath, $request->notes);
                $originalInstallment->update(['status' => 'paid']);
            } else {
                // --- PARTIAL PAYMENT LOGIC ---
                $partialAmount = (float) $request->partial_amount;
                $remainingAmount = $originalInstallment->amount - $partialAmount;

                if ($remainingAmount <= 0) {
                    throw new \Exception('Partial amount cannot be greater than or equal to the installment amount.');
                }

                // 1. Create invoice for the partial payment
                $this->handlePayment($originalInstallment, $tenant, $partialAmount, $request->payment_date, $receiptPath, $request->notes);

                // 2. Create a new installment for the remaining amount, due next month
                Installment::create([
                    'buyer_id' => $tenant->id,
                    'unit_id' => $originalInstallment->unit_id,
                    'installment_number' => $originalInstallment->installment_number + 0.1, // Or another way to denote it's a split
                    'due_date' => Carbon::parse($originalInstallment->due_date)->addMonth(),
                    'amount' => $remainingAmount,
                    'status' => 'pending',
                ]);

                // 3. Delete the original installment as it has now been split
                $originalInstallment->delete();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Installment Payment Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    /**
     * Helper function to create Invoice, InvoiceItem, and InvoicePayment.
     */
    private function handlePayment(Installment $installment, Tenant $tenant, $amount, $paymentDate, $receiptPath, $notes)
    {
        $invoice = Invoice::create([
            'invoice_id'    => 'INV-' . now()->format('Ymd') . '-' . $installment->id . '-' . rand(100, 999),
            'property_id'   => $tenant->property,
            'unit_id'       => $tenant->unit,
            'tenant_id'     => $tenant->id,
            'invoice_month' => Carbon::parse($installment->due_date)->startOfMonth()->format('Y-m-d'),
            'end_date'      => $installment->due_date,
            'status'        => 'paid',
        ]);

        InvoiceItem::create([
            'invoice_id'    => $invoice->id,
            'invoice_type'  => 'installment',
            'amount'        => $amount,
            'description'   => 'Payment for installment #' . $installment->installment_number,
        ]);

        InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'installment_id' => $installment->id,
            'payment_date' => $paymentDate,
            'amount' => $amount,
            'receipt' => $receiptPath,
            'notes' => $notes,
        ]);
    }
}
