<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF facade

class PdfExportController extends Controller
{
    /**
     * Generate and download a PDF for various model types.
     * Can handle both template-based generation and image-based generation for invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type The type of model to export.
     * @param  int  $id The ID of the model instance.
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request, $type, $id)
    {
        $data = [];
        $view = '';
        $fileName = 'document.pdf';

        // Use a switch statement to handle different export types
        switch ($type) {
            case 'tenant':
                $tenant = Tenant::with(['user', 'linked_property', 'propertyUnit', 'installments'])->find($id);
                if (!$tenant) {
                    return redirect()->back()->with('error', 'Tenant not found.');
                }
                $data = ['tenant' => $tenant];
                $view = 'pdf.tenant_details';
                $fileName = 'tenant_details_' . str_replace(' ', '_', optional($tenant->user)->first_name) . '.pdf';
                break;

            case 'invoice':
                if ($request->has('imageData')) {
                    // ✅ FIX: Instead of saving a file, we now pass the raw base64 data to the view.

                    // 1. Get the full data URI from the request.
                    $imageDataURI = $request->input('imageData');

                    // 2. Extract just the base64 part of the string.
                    list(, $base64Data) = explode(',', $imageDataURI);

                    // 3. Load the view, passing the base64 data.
                    $pdf = PDF::loadView('pdf.invoice_details', ['imageData' => $base64Data]);

                    return $pdf->stream('invoice_' . $id . '.pdf');
                } else {
                    // Fallback to the template-based PDF generation if no image is sent.
                    $invoice = Invoice::with(['property', 'unit', 'items', 'tenant.user'])->find($id);
                    if (!$invoice) {
                        return redirect()->back()->with('error', 'Invoice not found.');
                    }

                    $options = ['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans'];
                    $pdf = PDF::setOptions($options)->loadView('pdf.invoice_details', ['invoice' => $invoice]);

                    return $pdf->stream('invoice_' . $invoice->invoice_id . '.pdf');
                }
                break;

            default:
                return redirect()->back()->with('error', 'Invalid export type specified.');
        }

        // This part is now only for template-based PDFs.
        $options = ['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans'];
        $pdf = PDF::setOptions($options)->loadView($view, $data);
        return $pdf->stream($fileName);
    }
}
