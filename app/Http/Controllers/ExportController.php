<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport; // Import your Excel export class
use App\Models\Invoice;       // Import your Invoice model
use App\Models\Tenant;        // Import your Tenant model (if needed for relationships)
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Correct facade for Dompdf

class ExportController extends Controller
{
    /**
     * Export all invoices to Excel.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportInvoicesExcel()
    {
        // Name the file
        $fileName = 'invoices_' . now()->format('YmdHis') . '.xlsx';
        return Excel::download(new InvoicesExport, $fileName);
    }

    /**
     * Export a specific invoice to PDF.
     * @param int $id The ID of the invoice
     * @return \Illuminate\Http\Response
     */
    public function exportInvoicePdf($id)
    {
        $invoice = Invoice::with('tenant.user', 'payments')->find($id); // Eager load relationships

        if (!$invoice) {
            abort(404, 'Invoice not found');
        }

        // Fetch settings and logo path needed for the PDF view
        // Adjust these lines based on how your getSettingsValByName and settings helpers are defined
        $admin_logo = \App\Models\Utility::getSettingsValByName('company_logo');
        $settings = \App\Models\Utility::settings();
        $tenant = $invoice->tenant; // Pass the tenant for the invoice

        // Load the PDF view with all necessary data
        $pdf = Pdf::loadView('exports.invoices_pdf', compact('invoice', 'tenant', 'admin_logo', 'settings'));

        // Optional: Set paper size and orientation if needed (e.g., 'A4', 'landscape')
        // $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $fileName = 'invoice_' . \App\Helpers\Helper::invoicePrefix() . $invoice->invoice_id . '.pdf'; // Assuming Helper::invoicePrefix()
        return $pdf->download($fileName);
    }

    // You can add more export methods here for other tables/models following the same pattern
    // public function exportUsersExcel() { /* ... */ }
    // public function exportPropertiesPdf($id) { /* ... */ }
}
