@extends('layouts.app')

@section('page-title')
    {{ __('Invoices List') }}
@endsection



@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Invoices') }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 text-end">
            {{-- New: Export All Invoices to Excel Button --}}
            <button class="btn btn-success me-2 export-invoices-excel">
                <i class="ti ti-file-spreadsheet me-1"></i> {{ __('Export All (Excel)') }}
            </button>
            {{-- New: Export All Invoices to PDF Button --}}
            <button class="btn btn-danger me-2 export-invoices-pdf">
                <i class="ti ti-file-text me-1"></i> {{ __('Export All (PDF)') }}
            </button>
            {{-- Any other general actions for the invoice list can go here --}}
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- IMPORTANT: Added ID for JavaScript targeting --}}
                        <table class="table table-striped table-hover datatbl-advance1" id="invoiceListTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Month') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>{{ __('Paid') }}</th>
                                    <th>{{ __('Due') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Your controller should pass $invoices to this view for population --}}
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ invoicePrefix() . $invoice->invoice_id }}</td>
                                        <td>
                                            {{ !empty($invoice->tenant) && !empty($invoice->tenant->user) ? $invoice->tenant->user->first_name . ' ' . $invoice->tenant->user->last_name : '-' }}
                                        </td>
                                        <td>{{ date('F Y', strtotime($invoice->invoice_month)) }}</td>
                                        <td>{{ dateFormat($invoice->end_date) }}</td>
                                        <td>{{ priceFormat($invoice->getInvoiceTotalAmount()) }}</td>
                                        <td>{{ priceFormat($invoice->getInvoicePaidAmount()) }}</td>
                                        <td>{{ priceFormat($invoice->getInvoiceDueAmount()) }}</td>
                                        <td>
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
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                title="{{ __('View Invoice') }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            {{-- If you still want an individual PDF download here, it would use the backend route --}}
                                            {{-- <a href="{{ route('invoices.export.pdf', $invoice->id) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="{{__('Export PDF')}}">
                                            <i class="ti ti-file-text"></i>
                                        </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
