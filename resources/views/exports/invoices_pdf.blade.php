<!DOCTYPE html>
<html>

<head>
    <title>Invoice {{ invoicePrefix() . $invoice->invoice_id }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Define your PDF-specific CSS here */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            /* Crucial for Arabic/UTF-8 in Dompdf */
            font-size: 10pt;
            margin: 20mm;
            direction: rtl;
            /* For Right-to-Left languages like Arabic */
            text-align: right;
            /* Align text to the right for RTL */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
            /* Align table content to the right */
        }

        th {
            background-color: #f2f2f2;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details,
        .recipient-details {
            width: 48%;
            /* Adjust width to fit two columns */
            display: inline-block;
            /* Use inline-block for side-by-side */
            vertical-align: top;
            /* Align to the top */
            padding: 10px;
            box-sizing: border-box;
        }

        .invoice-details {
            float: right;
            /* Position invoice details to the right in RTL */
            text-align: right;
        }

        .recipient-details {
            float: left;
            /* Position recipient details to the left in RTL */
            text-align: right;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }

        .bg-primary {
            background-color: #0d6efd;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #000;
        }

        /* Ensure text is visible */

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Ensure logo fits, use public_path for src in PDF */
        .invoice-logo {
            max-height: 80px;
            width: auto;
        }

        /* PDF Footer positioning */
        .invoice-pdf-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            font-size: 8pt;
            border-top: 1px solid #eee;
            left: 0;
            /* Important for fixed positioning */
            right: 0;
            /* Important for fixed positioning */
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li strong {
            margin-left: 5px;
            /* Adjust spacing for RTL labels */
        }
    </style>
</head>

<body>
    <div class="clearfix">
        {{-- Recipient Details on the left (visual for RTL) --}}
        <div class="recipient-details">
            <img class="invoice-logo"
                src="{{ public_path(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png') }}"
                alt="company-logo">
            <h5 style="margin-top: 15px;">{{ __('Invoice to') }}:</h5>
            <ul>
                <li><strong>{{ __('Name') }}:</strong>
                    {{ !empty($tenant) && !empty($tenant->user) ? $tenant->user->first_name . ' ' . $tenant->user->last_name : '-' }}
                </li>
                <li><strong>{{ __('Phone') }}:</strong>
                    {{ !empty($tenant) && !empty($tenant->user) ? $tenant->user->phone_number : '-' }}
                </li>
                <li><strong>{{ __('Address') }}:</strong>
                    {{ !empty($tenant) ? $tenant->address : '-' }}
                </li>
                <li><strong>{{ __('National ID') }}:</strong>
                    {{ !empty($tenant) ? $tenant->zip_code : '-' }}
                </li>
            </ul>
        </div>
        {{-- Invoice Details on the right (visual for RTL) --}}
        <div class="invoice-details">
            <h2>{{ __('Invoice') }}</h2>
            <ul>
                <li><strong>{{ __('Status') }}:</strong>
                    @if ($invoice->status == 'open')
                        <span class="badge bg-primary">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                    @elseif($invoice->status == 'paid')
                        <span class="badge bg-success">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                    @elseif($invoice->status == 'partial_paid')
                        <span class="badge bg-warning">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                    @endif
                </li>
                <li><strong>{{ __('Invoice No') }}:</strong> {{ invoicePrefix() . $invoice->invoice_id }}</li>
                <li><strong>{{ __('Invoice Month') }}:</strong> {{ date('F Y', strtotime($invoice->invoice_month)) }}
                </li>
                <li><strong>{{ __('End Date') }}:</strong> {{ dateFormat($invoice->end_date) }}</li>
            </ul>
        </div>
    </div>
    <div class="clearfix"></div> <!-- Clear floats to prevent layout issues -->

    <h5 style="margin-top: 30px;">{{ __('Payment History') }}</h5>
    <table>
        <thead>
            <tr>
                <th>{{ __('Transaction Id') }}</th>
                <th>{{ __('Payment Date') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Notes') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->transaction_id }}</td>
                    <td>{{ dateFormat($payment->payment_date) }}</td>
                    <td>{{ priceFormat($payment->amount) }}</td>
                    <td>{{ __($payment->payment_type) }}</td>
                    <td>{{ $payment->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{ __('No payments found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="width: 50%; float: left; text-align: right; margin-top: 30px;">
        <table style="width: 100%; border: none;">
            <tbody>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>{{ __('Sub Total') }}:</strong></td>
                    <td style="border: none; padding: 5px;" class="text-right">
                        {{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>{{ __('Paid Amount') }}:</strong></td>
                    <td style="border: none; padding: 5px;" class="text-right">
                        {{ priceFormat($invoice->getInvoicePaidAmount()) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>{{ __('Due Amount') }}:</strong></td>
                    <td style="border: none; padding: 5px;" class="text-right">
                        {{ priceFormat($invoice->getInvoiceDueAmount()) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>{{ __('Grand Total') }}:</strong></td>
                    <td style="border: none; padding: 5px;" class="text-right">
                        <strong>{{ priceFormat($invoice->getInvoiceTotalAmount()) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>

    <div class="invoice-pdf-footer">
        <ul style="list-style: none; padding: 0; margin: 0; display: inline-block;">
            <li style="display: inline-block; margin: 0 10px;">{{ $settings['company_name'] ?? '-' }}</li>
            <li style="display: inline-block; margin: 0 10px;">{{ $settings['company_phone'] ?? '-' }}</li>
            <li style="display: inline-block; margin: 0 10px;">{{ $settings['company_email'] ?? '-' }}</li>
        </ul>
        <p style="margin-top: 5px; margin-bottom: 0;">&copy; {{ date('Y') }}
            {{ $settings['company_name'] ?? 'Your Company' }}. All rights reserved.</p>
    </div>
</body>

</html>
