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
    public function updateStatus(Installment $installment)
    {
        if ($installment->status === 'paid') {
            return redirect()->back()->with('error', 'This installment has already been paid.');
        }

        DB::beginTransaction();
        try {
            $tenant = $installment->buyer;

            if (!$tenant) {
                throw new \Exception('The buyer/tenant for this installment could not be found.');
            }

            // Action 1: Update the installment status.
            $installment->status = 'paid';
            $installment->save();

            // Action 2: Create the master Invoice record.
            $invoice = Invoice::create([
                'invoice_id'    => 'INV-' . now()->format('Ymd') . '-' . $installment->id,
                'property_id'   => $tenant->property,
                'unit_id'       => $tenant->unit,
                'invoice_month' => \Carbon\Carbon::parse($installment->due_date)->startOfMonth()->format('Y-m-d'),
                'end_date'      => $installment->due_date,
                'status'        => 'paid',
            ]);

            // ✅ Action 3: Create the related Invoice Item.
            InvoiceItem::create([
                'invoice_id'    => $invoice->id, // Use the ID from the invoice we just created.
                'invoice_type'  => 'installment',
                'amount'        => $installment->amount,
                'description'   => 'Payment for installment #' . $installment->installment_number,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Installment marked as paid and invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }
}
