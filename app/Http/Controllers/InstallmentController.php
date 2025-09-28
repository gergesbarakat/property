<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InstallmentController extends Controller
{
    /**
     * Show the form for creating a new payment record for an installment.
     */
    public function createPayment(Installment $installment)
    {
        return view('installments.payment', compact('installment'));
    }

    /**
     * Store a new payment, handle full or partial payments, and split installments if necessary.
     */
    public function storePayment(Request $request)
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

            $receiptPath = $request->file('receipt')->storeAs('receipts', 'public');

            if ($request->payment_type === 'full') {
                $originalInstallment->update(['status' => 'paid', 'paid_date' => $request->payment_date, 'notes' => $request->notes]);
                $this->createInvoiceAndPayment($originalInstallment, $tenant, $originalInstallment->amount, $request->payment_date, $receiptPath, $request->notes);
            } else {
                $partialAmount = (float) $request->partial_amount;
                $remainingAmount = $originalInstallment->amount - $partialAmount;

                if ($remainingAmount <= 0) {
                    throw new \Exception('Partial amount must be less than the installment amount.');
                }

                $paidInstallment = Installment::create([
                    'buyer_id' => $tenant->id,
                    'installment_number' => $originalInstallment->installment_number,
                    'due_date' => $request->payment_date,
                    'amount' => $partialAmount,
                    'status' => 'paid',
                    'paid_date' => $request->payment_date,
                    'notes' => 'Partial payment. ' . $request->notes,
                ]);

                $this->createInvoiceAndPayment($paidInstallment, $tenant, $partialAmount, $request->payment_date, $receiptPath, $request->notes);

                Installment::create([
                    'buyer_id' => $tenant->id,
                    'installment_number' => $originalInstallment->installment_number + 0.1,
                    'due_date' => $originalInstallment->due_date,
                    'amount' => $remainingAmount,
                    'status' => 'pending',
                    'notes' => 'Remaining balance from partial payment.',
                ]);

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
     * Helper function to create Invoice, InvoiceItem, and InvoicePayment records.
     */
    private function createInvoiceAndPayment(Installment $installment, Tenant $tenant, $paymentAmount, $paymentDate, $receiptPath, $notes)
    {
        // Step 1: Create the Invoice record, linked to the installment
        $invoice = Invoice::create([
            'invoice_id'    => 'INV-' . now()->format('Ymd') . '-' . $installment->id . '-' . rand(100, 999),
            'property_id'   => $tenant->property,
            'unit_id'       => $tenant->unit,
            'tenant_id'     => $tenant->id,
            'installment_id' => $installment->id,
            'invoice_month' => Carbon::parse($installment->due_date)->startOfMonth()->format('Y-m-d'),
            'end_date'      => $installment->due_date,
            'status'        => ($installment->status == 'paid' && $paymentAmount >= $installment->amount) ? 'paid' : 'partial_paid',
        ]);

        // Step 2: Create the associated Invoice Item
        InvoiceItem::create([
            'invoice_id'    => $invoice->id,
            'invoice_type'  => 'installment',
            'amount'        => $paymentAmount,
            'description'   => 'Payment for installment #' . $installment->installment_number,
        ]);

        // ✅ FIX: The re-query has been removed. We now use the $invoice object directly.
        // Step 3: Create the Invoice Payment record using the original invoice's ID.
        InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'installment_id' => $installment->id,
            'payment_date' => $paymentDate,
            'amount' => $paymentAmount,
            'receipt' => $receiptPath,
            'notes' => $notes,
        ]);
    }
}
