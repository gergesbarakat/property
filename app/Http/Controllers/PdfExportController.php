<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Property;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF facade

class PdfExportController extends Controller
{
    /**
     * Generate and download a PDF for various model types.
     *
     * @param  string  $type The type of model to export (e.g., 'tenant', 'invoice', 'property').
     * @param  int  $id The ID of the model instance.
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($type, $id)
    {
        $data = [];
        $view = '';
        $fileName = 'document.pdf';

        // Use a switch statement to handle different export types
        switch ($type) {
            case 'tenant':
                // Find the tenant and load all its necessary relationships
                $tenant = Tenant::with(['user', 'linked_property', 'propertyUnit', 'installments'])->find($id);

                if (!$tenant) {
                    return redirect()->back()->with('error', 'Tenant not found.');
                }

                // Prepare the data for the view
                $data = ['tenant' => $tenant];
                $view = 'pdf.tenant_details';
                $fileName = 'tenant_details_' . str_replace(' ', '_', optional($tenant->user)->first_name) . '.pdf';
                break;

            case 'invoice':
                // First, find the invoice and its direct items.
                $invoice = Invoice::with(['property', 'unit', 'items'])->find($id);

                if (!$invoice) {
                    return redirect()->back()->with('error', 'Invoice not found.');
                }

                // Second, find the tenant separately using the invoice's details.
                $tenant = Tenant::where('property', $invoice->property_id)
                    ->where('unit', $invoice->unit_id)
                    ->with('user') // Eager load the user details
                    ->first();

                // Pass both the invoice and the tenant to the view.
                $data = [
                    'invoice' => $invoice,
                    'tenant' => $tenant,
                ];
                $view = 'pdf.invoice_details';
                $fileName = 'invoice_' . $invoice->invoice_id . '.pdf';
                break;

            // You can add more cases here for other types like 'property'

            default:
                // Handle unknown types
                return redirect()->back()->with('error', 'Invalid export type specified.');
        }

        // ✅ FIX: Set domPDF options to correctly handle Arabic and complex HTML.
        $options = [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ];

        // Load the appropriate view with the fetched data and options
        $pdf = PDF::setOptions($options)->loadView($view, $data);

        // Stream the generated PDF to the browser for download
        return $pdf->stream($fileName);
    }
}
