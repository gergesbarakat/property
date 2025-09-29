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

        .user-card .user-imgwrap {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }

        .user-card .user-imgwrap img {
            width: 100px;
            height: 100px;
            border: 5px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .user-card .card-body {
            margin-top: 60px;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .financial-summary .stat-card {
            background-color: #f8f9fa;
            border-radius: .5rem;
            padding: 1rem;
            text-align: center;
        }

        .financial-summary .stat-card .icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .financial-summary .stat-card h6 {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .financial-summary .stat-card p {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .info-card .media-body h6 {
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-card .media-body p {
            font-weight: 500;
        }

        .installments-table {
            border-collapse: collapse;
            width: 100%;
        }

        .installments-table thead th {
            font-weight: 600;
            background-color: #fff;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .installments-table td,
        .installments-table th {
            vertical-align: middle !important;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="section">
            <table class="info-table">
                <tr>
                    <td>
                        <img class="img-fluid" style="width: 150px; height: auto;"
                            src="{{ asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png') }}"
                            alt="theeme-logo">
                    </td>
                    <td>
                        Bill To:
                        <strong>{{ optional($tenant->user)->first_name }}
                            {{ optional($tenant->user)->last_name }}</strong><br>
                        {{ $tenant->address ?? '-' }}<br>
                        {{ $tenant->city ?? '' }}, {{ $tenant->state ?? '' }} <br>
                        {{ optional($tenant->user)->email ?? '-' }}<br>
                        ID:{{ $tenant->zip_code ?? '' }}
                    </td>
                    <td>Property Details:
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
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h4>{{ __('Financial Summary') }}</h4>
                </div>
                <table class="card-body financial-summary">
                    <tr>
                        <td>
                            <div class="stat-card">
                                <div class="icon text-primary"><i data-feather="dollar-sign"></i></div>
                                <h6>Total Amount</h6>
                                <p>{{ number_format($tenant->financial_summary['total_amount'], 2) }} EGP</p>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="icon text-success"><i data-feather="check-circle"></i></div>
                                <h6>Amount Paid</h6>
                                <p>{{ number_format($tenant->financial_summary['paid_amount'], 2) }} EGP</p>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="icon text-danger"><i data-feather="trending-down"></i></div>
                                <h6>Amount Due</h6>
                                <p>{{ number_format($tenant->financial_summary['due_amount'], 2) }} EGP</p>
                            </div>
                        </td>
                    </tr>
                </table>

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
