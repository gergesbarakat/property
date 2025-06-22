<?php

namespace App\Exports;

use App\Models\Invoice; // Make sure this path is correct for your Invoice model
use App\Models\User;   // Assuming User model is related to Tenant for names
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Optional: Auto-adjust column widths
use Maatwebsite\Excel\Concerns\WithMapping;   // Optional: Map data to rows/columns

class InvoicesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // This will fetch ALL invoices.
        // If you need to filter or order, do it here (e.g., Invoice::where('status', 'paid')->get())
        return Invoice::with('tenant.user')->get(); // Eager load relationships for efficiency
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Invoice ID',
            'Tenant Name',
            'Invoice Month',
            'End Date',
            'Total Amount',
            'Paid Amount',
            'Due Amount',
            'Status',
            'Created At',
            // Add any other desired column headers here
        ];
    }

    /**
     * Map each row of data to the specified headings.
     * @param mixed $invoice
     * @return array
     */
    public function map($invoice): array
    {
        // Ensure these methods and relationships exist on your Invoice model
        // and its related models (Tenant, User).
        $tenantName = !empty($invoice->tenant) && !empty($invoice->tenant->user)
            ? $invoice->tenant->user->first_name . ' ' . $invoice->tenant->user->last_name
            : '-';

        return [
            invoicePrefix() . $invoice->invoice_id, // Assuming invoicePrefix() helper exists
            $tenantName,
            date('F Y', strtotime($invoice->invoice_month)),
            dateFormat($invoice->end_date), // Assuming dateFormat() helper exists
            priceFormat($invoice->getInvoiceTotalAmount()), // Ensure this method exists
            priceFormat($invoice->getInvoicePaidAmount()),   // Ensure this method exists
            priceFormat($invoice->getInvoiceDueAmount()),    // Ensure this method exists
            \App\Models\Invoice::$status[$invoice->status], // Assuming status array
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
