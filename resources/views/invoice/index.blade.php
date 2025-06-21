@extends('layouts.app')
@section('page-title')
    {{ __('Invoice') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Invoice') }}</a>
        </li>
    </ul>
@endsection
{{-- @section('card-action-btn')
    @can('create invoice')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('invoice.create') }}"> <i
                class="ti-plus mr-5"></i>{{ __('Create Invoice') }}</a>
    @endcan
@endsection --}}



@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable   datatbl-advance">
                        <thead>
                            <tr>
                                <th>{{ __('Invoice') }}</th>
                                <th>{{ __('Property') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Invoice Month') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('type') }}</th>

                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th class="text-right">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr role="row">
                                    <td>{{ $invoice->invoice_id }}</td>
                                    <td>{{ optional($invoice->property)->name ?? '-' }}</td>
                                    <td>{{ optional($invoice->unit)->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_month)->format('F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->end_date)->format('M j, Y') }}</td>

                                    {{-- ✅ CORRECTED: Calculates the total amount by summing all item amounts --}}
                                    <td>${{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                    <td>{{ $invoice->payments->first()->notes }}</td>
                                    <td>
                                        @if ($invoice->status == 'paid')
                                            <span class="badge badge-success">{{ ucfirst($invoice->status) }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ ucfirst($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                             <a class="dropdown-item" href="{{ route('invoice.show', $invoice->id) }}">
                                                <i class="ti-eye"></i> {{ __('View') }}
                                            </a>

 

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
