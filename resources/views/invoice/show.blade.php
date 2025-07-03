@extends('layouts.app')
@section('page-title')
    {{__('Invoice')}}
@endsection
@php
    $admin_logo=getSettingsValByName('company_logo');
    $settings=settings();
@endphp
@push('script-page')
    <script>
        // This script is for the old print function, which can be kept as a secondary option if needed,
        // but the main button will now use the PDF controller.
        $(document).on('click', '.print-invoice-btn', function () {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    </script>
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('invoice.index')}}">{{__('Invoice')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Details')}}</a>
        </li>
    </ul>
@endsection
@section('content')

    <div class="row mb-10">
        <div class="invoice-action ">
            {{-- ✅ FIX: This button now correctly uses the PdfExportController route --}}
            <a class="btn btn-primary float-end" href="{{ route('pdf.download', ['type' => 'invoice', 'id' => $invoice->id]) }}" target="_blank">
                <i data-feather="download" class="me-1"></i> {{__('Export as PDF')}}
            </a>

            @if($invoice->status!='paid')
                @can('create invoice payment')
                    @if(\Auth::user()->type=='tenant')
                        <a class="btn btn-secondary float-end me-2 collapsed" data-bs-toggle="collapse"
                           href="#paymentModal" role="button" aria-expanded="false"
                           aria-controls="collapse1">{{__('Payment')}}</a>
                    @else
                        <a class="btn btn-secondary float-end me-2 customModal" href="#" data-size="md"
                           data-url="{{ route('invoice.payment.create',$invoice->id) }}"
                           data-title="{{__('Add Payment')}}"> {{__('Add Payment')}}</a>
                    @endif
                @endcan
            @endif
        </div>
    </div>

    {{-- Payment Modals would go here --}}

    <div id="invoice-print">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body cdx-invoice">
                        <div id="cdx-invoice">
                            <div class="head-invoice">
                                <div class="codex-brand">
                                    <a class="codexbrand-logo" href="Javascript:void(0);">
                                        <img class="img-fluid invoice-logo" src=" {{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}" alt="invoice-logo">
                                    </a>
                                </div>
                                <ul class="contact-list">
                                    <li><div class="icon-wrap"><i class="fa fa-user"></i></div>{{$settings['company_name'] ?? ''}}</li>
                                    <li><div class="icon-wrap"><i class="fa fa-phone"></i></div>{{$settings['company_phone'] ?? ''}}</li>
                                    <li><div class="icon-wrap"><i class="fa fa-envelope"></i></div>{{$settings['company_email'] ?? ''}}</li>
                                </ul>
                            </div>
                            <div class="invoice-user">
                                <div class="left-user">
                                    <h5>{{__('Inovice to')}}:</h5>
                                    <ul class="detail-list">
                                        <li><div class="icon-wrap"><i class="fa fa-user"></i></div>{{ $invoice->tenant?->user?->first_name }} {{ $invoice->tenant?->user?->last_name }}</li>
                                        <li><div class="icon-wrap"><i class="fa fa-phone"></i></div>{{ $invoice->tenant?->user?->phone_number ?? '-'}}</li>
                                        <li><div class="icon-wrap"><i class="fa fa-map-marker"></i></div>{{ $invoice->tenant?->address ?? ''}}</li>
                                    </ul>
                                </div>
                                <div class="right-user">
                                    <ul class="detail-list">
                                        <li>{{__('Status')}}:
                                            @if($invoice->status=='paid')
                                                <span class="badge badge-success">{{ ucfirst($invoice->status) }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ ucfirst($invoice->status) }}</span>
                                            @endif
                                        </li>
                                        <li>{{__('Invoice No')}}: <span>{{ $invoice->invoice_id }} </span></li>
                                        <li>{{__('Invoice Month')}}:<span> {{ \Carbon\Carbon::parse($invoice->invoice_month)->format('F Y') }} </span></li>
                                        <li>{{__('End Date')}}: <span>{{ \Carbon\Carbon::parse($invoice->end_date)->format('M j, Y') }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="body-invoice">
                                <div class="table-responsive1">
                                    <table class="table ml-1">
                                        <thead><tr><th>{{__('Type')}}</th><th>{{__('Description')}}</th><th>{{__('Amount')}}</th></tr></thead>
                                        <tbody>
                                            @foreach($invoice->items as $item)
                                                <tr>
                                                    <td>{{$item->invoice_type}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>${{number_format($item->amount, 2)}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="footer-invoice">
                                <table class="table">
                                    <tr>
                                        <td>{{__('Total')}}</td>
                                        <td>${{number_format($invoice->items->sum('amount'), 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Due Amount')}}</td>
                                        <td>${{number_format($invoice->items->sum('amount'), 2)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>{{__('Payment History')}}</h5></div>
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance1">
                        <thead>
                            <tr>
                                <th>{{__('Transaction Id')}}</th>
                                <th>{{__('Payment Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Notes')}}</th>
                                <th>{{__('Receipt')}}</th>
                                @can('delete invoice payment')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr role="row">
                                    <td>{{$payment->transaction_id}} </td>
                                    <td>{{dateFormat($payment->payment_date)}} </td>
                                    <td>{{priceFormat($payment->amount)}} </td>
                                    <td>{{__($payment->payment_type)}} </td>
                                    <td>{{$payment->notes}} </td>
                                    <td>
                                        @if(!empty($payment->receipt))
                                            @if($payment->payment_type=='Stripe')
                                                <a href="{{$payment->receipt}}" target="_blank"><i data-feather="eye"></i></a>
                                            @else
                                                <a href="{{asset(Storage::url('upload/receipt')).'/'.$payment->receipt}}" download="download"><i data-feather="download"></i></a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @can('delete invoice payment')
                                        <td class="text-right">
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.destroy', $invoice->id,$payment->id]]) !!}
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
