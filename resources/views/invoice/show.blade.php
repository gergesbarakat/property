@extends('layouts.app')

@section('page-title')
    {{ __('Invoice Details') }}
@endsection

@php
    $admin_logo = getSettingsValByName('company_logo');
    $settings = settings();
    $logo_path =
        asset(Storage::url('upload/logo/')) .
        '/' .
        (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png');
@endphp

@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Print functionality
        $(document).on('click', '.print-invoice', function() {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            // Add a class to the body for print-specific styles
            document.body.classList.add('invoice-print-mode');

            window.print();

            // Revert back to original content after printing
            document.body.innerHTML = originalContents;
            document.body.classList.remove('invoice-print-mode'); // Remove the class
            // Re-attach event listeners if necessary, depending on your SPA setup
        });

        // Stripe Payment Integration
        @if (
            $invoicePaymentSettings['STRIPE_PAYMENT'] == 'on' &&
                !empty($invoicePaymentSettings['STRIPE_KEY']) &&
                !empty($invoicePaymentSettings['STRIPE_SECRET']))
            var stripe = Stripe('{{ $invoicePaymentSettings['STRIPE_KEY'] }}');
            var elements = stripe.elements();
            var style = {
                base: {
                    fontSize: '16px', // Slightly larger font for better readability
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a',
                },
            };
            var card = elements.create('card', {
                style: style
            });
            card.mount('#card-element');

            var form = document.getElementById('stripe-payment');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $('#card-errors').html(result.error.message)
                            .show(); // Display errors in the dedicated div
                        $.NotificationApp.send("Error", result.error.message, "top-right",
                            "rgba(0,0,0,0.2)", "error");
                    } else {
                        var token = result.token;
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', token.id);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        @endif
    </script>

    {{-- Custom styles for invoice printing --}}
    <style>
        /* These styles will apply when printing */
        @media print {

            html,
            body {
                height: 100%;
                /* Make HTML and Body take full height */
                margin: 0 !important;
                padding: 0 !important;
                /* Optional: Adjust font size for print for better readability */
                font-size: 11pt;
            }

            /* Ensure the content itself stretches to fill space */
            #invoice-print {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                /* Minimum viewport height */
                justify-content: space-between;
                /* Pushes footer to bottom */
            }

            #invoice-print>.row {
                flex-grow: 1;
                /* Allow the content row to expand */
                display: flex;
                flex-direction: column;
            }

            #invoice-print .card {
                flex-grow: 1;
                /* Allow the card to expand */
                display: flex;
                flex-direction: column;
            }

            #invoice-print .card-body {
                flex-grow: 1;
                /* Allow the card body to expand */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* Space out elements within card body */
            }

            .invoice-container {
                flex-grow: 1;
                /* Allow the container to take available space */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* Pushes footer to bottom */
            }

            /* Add space between the last table and the footer */
            .invoice-items+.row.justify-content-end {
                margin-bottom: 2rem !important;
                /* Add more space above the totals table */
            }

            .invoice-footer {
                margin-top: auto;
                /* Pushes the footer to the bottom of the flex container */
                padding-top: 1.5rem !important;
                /* Adjust as needed */
                border-top: 1px solid #dee2e6;
                /* Ensure border is visible on print */
            }

            /* Hide elements not needed for printing */
            .mb-4,
            /* Actions row */
            .collapse,
            /* Payment options */
            .breadcrumb-item,
            /* Breadcrumbs */
            .header-navbar,
            /* Your main navigation */
            .sidebar,
            /* Your sidebar */
            .footer-main

            /* Your application's main footer, if separate */
                {
                display: none !important;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Details') }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row mb-4" style="height: 100%">
        <div class="col-12 text-end">
            <a class="btn btn-primary print-invoice me-2" href="javascript:void(0);">
                <i class="ti ti-printer me-1"></i> {{ __('Print Invoice') }}
            </a>
            @if ($invoice->status != 'paid')
                @can('create invoice payment')
                    @if (\Auth::user()->type == 'tenant')
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#paymentModal"
                            aria-expanded="false" aria-controls="paymentModal">
                            <i class="ti ti-cash me-1"></i> {{ __('Make Payment') }}
                        </button>
                    @else
                        <a class="btn btn-success customModal" href="#" data-size="md"
                            data-url="{{ route('invoice.payment.create', $invoice->id) }}"
                            data-title="{{ __('Add Payment') }}">
                            <i class="ti ti-cash me-1"></i> {{ __('Add Payment') }}
                        </a>
                    @endif
                @endcan
            @endif
        </div>
    </div>

    @if ($invoice->status != 'paid' && \Auth::user()->type == 'tenant')
        <div class="collapse mb-4" id="paymentModal">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Payment Options') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @if ($settings['bank_transfer_payment'] == 'on')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-bank-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-bank-transfer" type="button" role="tab"
                                    aria-controls="pills-bank-transfer"
                                    aria-selected="true">{{ __('Bank Transfer') }}</button>
                            </li>
                        @endif
                        @if ($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET']))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $settings['bank_transfer_payment'] != 'on' ? 'active' : '' }}"
                                    id="pills-stripe-tab" data-bs-toggle="pill" data-bs-target="#pills-stripe-payment"
                                    type="button" role="tab" aria-controls="pills-stripe-payment"
                                    aria-selected="{{ $settings['bank_transfer_payment'] != 'on' ? 'true' : 'false' }}">{{ __('Stripe') }}</button>
                            </li>
                        @endif
                        @if (
                            $settings['paypal_payment'] == 'on' &&
                                !empty($settings['paypal_client_id']) &&
                                !empty($settings['paypal_secret_key']))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-paypal-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-paypal-payment" type="button" role="tab"
                                    aria-controls="pills-paypal-payment" aria-selected="false">{{ __('Paypal') }}</button>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- Bank Transfer Payment --}}
                        @if ($settings['bank_transfer_payment'] == 'on')
                            <div class="tab-pane fade {{ $settings['bank_transfer_payment'] == 'on' ? 'show active' : '' }}"
                                id="pills-bank-transfer" role="tabpanel" aria-labelledby="pills-bank-tab">
                                <form
                                    action="{{ route('invoice.banktransfer.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark">{{ __('Bank Name') }}:</label>
                                            <p class="form-control-static">{{ $settings['bank_name'] ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark">{{ __('Bank Holder Name') }}:</label>
                                            <p class="form-control-static">{{ $settings['bank_holder_name'] ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark">{{ __('Bank Account Number') }}:</label>
                                            <p class="form-control-static">{{ $settings['bank_account_number'] ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark">{{ __('Bank IFSC Code') }}:</label>
                                            <p class="form-control-static">{{ $settings['bank_ifsc_code'] ?? '-' }}</p>
                                        </div>
                                        @if (!empty($settings['bank_other_details']))
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label text-dark">{{ __('Bank Other Details') }}:</label>
                                                <p class="form-control-static">{{ $settings['bank_other_details'] }}</p>
                                            </div>
                                        @endif
                                        <div class="col-md-6 mb-3">
                                            <label for="amount" class="form-label text-dark">{{ __('Amount') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount" class="form-control"
                                                value="{{ $invoice->getInvoiceDueAmount() }}"
                                                placeholder="{{ __('Enter Amount') }}" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="receipt" class="form-label text-dark">{{ __('Attachment') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="file" name="receipt" id="receipt" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="notes"
                                                class="form-label text-dark">{{ __('Notes') }}</label>
                                            <input type="text" name="notes" id="notes" class="form-control"
                                                placeholder="{{ __('Enter notes') }}">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary">{{ __('Pay') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Stripe Payment --}}
                        @if ($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET']))
                            <div class="tab-pane fade {{ $settings['bank_transfer_payment'] != 'on' ? 'show active' : '' }}"
                                id="pills-stripe-payment" role="tabpanel" aria-labelledby="pills-stripe-tab">
                                <form
                                    action="{{ route('invoice.stripe.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                    method="post" id="stripe-payment">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="amount_stripe" class="form-label text-dark">{{ __('Amount') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount_stripe" class="form-control"
                                                value="{{ $invoice->getInvoiceDueAmount() }}"
                                                placeholder="{{ __('Enter Amount') }}" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="card-name-on"
                                                class="form-label text-dark">{{ __('Card Holder Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="card-name-on" class="form-control"
                                                placeholder="{{ __('Card Holder Name') }}" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="card-element"
                                                class="form-label text-dark">{{ __('Card Details') }} <span
                                                    class="text-danger">*</span></label>
                                            <div id="card-element" class="form-control">
                                            </div>
                                            <div id="card-errors" class="text-danger mt-2" role="alert"
                                                style="display: none;"></div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary">{{ __('Pay Now') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- PayPal Payment --}}
                        @if (
                            $settings['paypal_payment'] == 'on' &&
                                !empty($settings['paypal_client_id']) &&
                                !empty($settings['paypal_secret_key']))
                            <div class="tab-pane fade" id="pills-paypal-payment" role="tabpanel"
                                aria-labelledby="pills-paypal-tab">
                                <form
                                    action="{{ route('invoice.paypal', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                    method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="amount_paypal" class="form-label text-dark">{{ __('Amount') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount_paypal" class="form-control"
                                                value="{{ $invoice->getInvoiceDueAmount() }}"
                                                placeholder="{{ __('Enter Amount') }}" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary">{{ __('Pay Now') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="invoice-print" style="height: 100%">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-container">
                            {{-- Company logo remains in header --}}
                            <div class="logo-area">
                                <a href="Javascript:void(0);" class="d-block">
                                    <img class="img-fluid invoice-logo" src="{{ $logo_path }}" alt="company-logo">
                                </a>
                            </div>
                            <div class="invoice-header d-flex justify-content-between align-items-start mb-4">

                                <div class="invoice-details text-end">
                                    <h2 class="mb-2">{{ __('Invoice') }}</h2>
                                    <ul class="list-unstyled">
                                        <li><strong>{{ __('Status') }}:</strong>
                                            @if ($invoice->status == 'open')
                                                <span
                                                    class="badge bg-primary">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'paid')
                                                <span
                                                    class="badge bg-success">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'partial_paid')
                                                <span
                                                    class="badge bg-warning">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @endif
                                        </li>
                                        <li><strong>{{ __('Invoice No') }}:</strong>
                                            {{ invoicePrefix() . $invoice->invoice_id }}</li>
                                        <li><strong>{{ __('Invoice Month') }}:</strong>
                                            {{ date('F Y', strtotime($invoice->invoice_month)) }}</li>
                                        <li><strong>{{ __('End Date') }}:</strong> {{ dateFormat($invoice->end_date) }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="invoice-recipient mb-5">
                                    <h5 class="mb-3">{{ __('Invoice to') }}:</h5>
                                    <ul class="list-unstyled">
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
                            </div>


                            <hr class="my-4">


                            <div class="invoice-items mt-100 mb-5">
                                 <div class="table-responsive">
                                    <table class="table border table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Transaction Id') }}</th>
                                                <th>{{ __('Payment Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Notes') }}</th>
                                                {{-- @can('delete invoice payment')
                                                    <th class="text-end">{{ __('Action') }}</th>
                                                @endcan --}}
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
                                                    {{-- @can('delete invoice payment')
                                                        <td class="text-end">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.destroy', $invoice->id, $payment->id], 'id' => 'delete-form-' . $payment->id]) !!}
                                                            <a href="#" class="btn btn-sm btn-danger bs-pass-desk-btn" data-bs-toggle="tooltip" title="{{ __('Delete') }}" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action cannot be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </td>
                                                    @endcan --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">{{ __('No payments found.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row justify-content-end invoice-totals-section"> {{-- Added class for targeting --}}
                                <div class="col-md-5">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                {{-- <tr>
                                                    <td><strong>{{ __('Sub Total') }}:</strong></td>
                                                    <td class="text-end">
                                                        {{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}</td>
                                                </tr> --}}
                                                {{-- <tr>
                                                     <strong>{{ __('Paid Amount') }}:</strong>
                                                         {{ priceFormat($invoice->getInvoicePaidAmount()) }}
                                                </tr> --}}
                                                {{-- <tr>
                                                    <td><strong>{{ __('Due Amount') }}:</strong></td>
                                                    <td class="text-end">
                                                        {{ priceFormat($invoice->getInvoiceDueAmount()) }}</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <td><strong>{{ __('Grand Total') }}:</strong></td>
                                                    <td class="text-end">
                                                        <strong>{{ priceFormat($invoice->getInvoiceTotalAmount()) }}</strong>
                                                    </td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Invoice Footer with Company Details --}}
                            <div class="invoice-footer text-center mt-100 pt-4 border-top">
                                <ul
                                    class="list-unstyled contact-list d-flex justify-content-center align-items-center mb-0">
                                    <li class="mx-3">
                                        <i class="ti ti-user me-1"></i>{{ $settings['company_name'] ?? '-' }}
                                    </li>
                                    <li class="mx-3">
                                        <i class="ti ti-phone me-1"></i>{{ $settings['company_phone'] ?? '-' }}
                                    </li>
                                    <li class="mx-3">
                                        <i class="ti ti-mail me-1"></i>{{ $settings['company_email'] ?? '-' }}
                                    </li>
                                    {{-- Add company address or other footer details here if needed --}}
                                </ul>
                                <p class="mt-2 text-muted">&copy; {{ date('Y') }}
                                    {{ $settings['company_name'] ?? 'Your Company' }}. All rights reserved.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
