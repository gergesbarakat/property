<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Statement</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header-table,
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-logo {
            width: 150px;
        }

        .company-details {
            text-align: right;
        }

        .company-details h1 {
            margin: 0;
            font-size: 28px;
            color: #000;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f8f8f8;
            padding: 8px;
            border-radius: 5px;
        }

        .info-table td {
            padding: 5px 0;
        }

        .info-table .label {
            font-weight: bold;
            width: 120px;
        }

        .installments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .installments-table th,
        .installments-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .installments-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header Section with Company Logo and Details --}}
        <table class="header-table">
            <tr>
                <td>
                    {{-- Replace with your company's logo URL --}}
                    <img src="https://via.placeholder.com/150x60.png?text=Your+Logo" alt="Company Logo"
                        class="company-logo">
                </td>
                <td class="company-details">
                    <h1>STATEMENT</h1>
                    <p>
                        <strong>Your Company Name</strong><br>
                        123 Main Street, Suite 456<br>
                        Anytown, ST 12345<br>
                        contact@yourcompany.com
                    </p>
                </td>
            </tr>
        </table>

        <hr>

        {{-- Tenant and Property Information Section --}}
        <div class="section">
            <table class="info-table">
                <tr>
                    <td class="label">Bill To:</td>
                    <td>
                        <strong>{{ optional($tenant->user)->first_name }}
                            {{ optional($tenant->user)->last_name }}</strong><br>
                        {{ $tenant->address ?? '-' }}<br>
                        {{ $tenant->city ?? '' }}, {{ $tenant->state ?? '' }} {{ $tenant->zip_code ?? '' }}<br>
                        {{ optional($tenant->user)->email ?? '-' }}
                    </td>
                    <td class="label">Property Details:</td>
                    <td>
                        <strong>{{ optional($tenant->linked_property)->name ?? '-' }}</strong><br>
                        Unit: {{ optional($tenant->propertyUnit)->name ?? '-' }}<br>
                        Generated on: {{ now()->format('F j, Y') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- ✅ NEW: Unit Details Section --}}
        <div class="section">
            <div class="section-title">Unit Details</div>
            <table class="info-table">
                <tr>
                    <td class="label">Bedrooms:</td>
                    <td>{{ optional($tenant->propertyUnit)->bedroom ?? '-' }}</td>
                    <td class="label">Baths:</td>
                    <td>{{ optional($tenant->propertyUnit)->baths ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kitchens:</td>
                    <td>{{ optional($tenant->propertyUnit)->kitchen ?? '-' }}</td>
                    <td class="label">Status:</td>
                    <td>{{ ucfirst(optional($tenant->propertyUnit)->status) ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- Installment Plan Section --}}
        @if ($tenant->installments->isNotEmpty())
            <div class="section">
                <div class="section-title">Installment Plan</div>
                <table class="installments-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenant->installments as $installment)
                            <tr>
                                <td>{{ $installment->installment_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('M j, Y') }}</td>
                                <td>${{ number_format($installment->amount, 2) }}</td>
                                <td>{{ ucfirst($installment->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
