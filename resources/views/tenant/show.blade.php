@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Details') }}
@endsection
@section('page-class')
    cdxuser-profile
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><h1>{{ __('Dashboard') }}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tenant.index') }}">{{ __('Tenant') }}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{ __('Details') }}</a></li>
    </ul>
@endsection

@section('card-action-btn')
    <button id="export-pdf-btn" class="btn btn-primary btn-sm">
        <i data-feather="download" class="me-1" style="width:16px; height:16px;"></i>
        {{ __('Export as PDF') }}
    </button>
@endsection


@section('styles')
    <style>
        .user-card .user-imgwrap { position: absolute; top: -50px; left: 50%; transform: translateX(-50%); }
        .user-card .user-imgwrap img { width: 100px; height: 100px; border: 5px solid #fff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .user-card .card-body { margin-top: 60px; }
        .consolidated-table .section-header td { background-color: #f8f9fa; font-size: 1.1rem; font-weight: 600; color: #495057; padding: 0.75rem 1.25rem; border-top: 2px solid #dee2e6; }
        .consolidated-table .info-label { font-weight: 600; color: #6c757d; width: 20%; }
        .consolidated-table .sub-header th { background-color: #f8f9fa; text-transform: uppercase; font-size: .8rem; font-weight: 600; letter-spacing: .5px; }
        .badge { font-size: 0.8rem; padding: 0.5em 0.75em; }
    </style>
@endsection

@section('content')
    {{-- Session Alert Messages --}}
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <div id="printableArea">
        <div class="row">
            {{-- Left Card: User Profile --}}
            <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
                <div class="card user-card">
                    <div class="card-header" style="min-height: 50px;"></div>
                    <div class="card-body text-center">
                        <div class="user-imgwrap"><img class="img-fluid rounded-circle"
                                src="{{ optional($tenant->user)->profile ? Storage::url($tenant->user->profile) : asset('path/to/default/avatar.png') }}"
                                alt="Profile Image"></div>
                        <div class="user-detailwrap">
                            <h3>{{ optional($tenant->user)->first_name }} {{ optional($tenant->user)->last_name }}</h3>
                            <h6>{{ optional($tenant->user)->email ?? '-' }}</h6>
                            <h6>{{ optional($tenant->user)->phone_number ?? '-' }}</h6>
                            @if ($tenant->contracts->isNotEmpty())
                                <a href="{{ route('tenants.contracts.download', $tenant->id) }}"
                                    class="btn btn-primary btn-sm mt-3">
                                    <i data-feather="download-cloud" class="me-1" style="width:16px; height:16px;"></i>
                                    {{ __('Download Documents') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Card: Consolidated Information Table --}}
            <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered consolidated-table">
                                <tbody>
                                    <tr class="section-header"><td colspan="4"><h4>{{ __('Tenant Information') }}</h4></td></tr>
                                    <tr><td class="info-label">{{ __('Name') }}</td><td colspan="3">{{ optional($tenant->user)->first_name }} {{ optional($tenant->user)->last_name }}</td></tr>
                                    <tr><td class="info-label">{{ __('Email') }}</td><td>{{ optional($tenant->user)->email ?? '-' }}</td><td class="info-label">{{ __('Phone') }}</td><td>{{ optional($tenant->user)->phone_number ?? '-' }}</td></tr>
                                    <tr><td class="info-label">{{ __('Property') }}</td><td>{{ optional($tenant->linked_property)->name ?? '-' }}</td><td class="info-label">{{ __('Unit') }}</td><td>{{ optional($tenant->propertyUnit)->name ?? '-' }}</td></tr>
                                    <tr><td class="info-label">{{ __('Address') }}</td><td colspan="3">{{ $tenant->address ?? '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Installment Plan Card --}}
    @if ($tenant->installments->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>{{ __('Installment Plan') }}</h4></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="installments-table">
                                <thead>
                                    <tr><th>#</th><th>Due Date</th><th>Amount</th><th class="text-center">Status & Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenant->installments as $installment)
                                        <tr>
                                            <td>{{ $installment->installment_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('F j, Y') }}</td>
                                            <td>${{ number_format($installment->amount, 2) }}</td>
                                            <td class="text-center">
                                                @if ($installment->status == 'paid')
                                                    <span class="badge bg-success text-white">Paid</span>
                                                @elseif($installment->status == 'pending')
                                                    <button type="button" class="btn btn-sm btn-outline-success open-payment-modal"
                                                            data-bs-toggle="modal" data-bs-target="#paymentModal"
                                                            data-installment-id="{{ $installment->id }}"
                                                            data-amount="{{ $installment->amount }}">
                                                        Record Payment
                                                    </button>
                                                @else
                                                    <span class="badge bg-danger text-white">Overdue</span>
                                                @endif
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
    @endif

    {{-- Payment Modal with Receipt Upload --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Record Payment & Upload Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('installment.payment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="installment_id" id="modal_installment_id">
                        <input type="hidden" name="amount" id="modal_total_amount">

                        <div class="mb-3">
                            <label for="payment_type" class="form-label">Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control">
                                <option value="full">Full Payment</option>
                                <option value="partial">Partial Payment</option>
                            </select>
                        </div>

                        <div class="mb-3" id="partial_amount_div" style="display: none;">
                            <label for="modal_partial_amount" class="form-label">Partial Amount Paid</label>
                            <input type="number" class="form-control" id="modal_partial_amount" name="partial_amount" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label for="modal_payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="modal_payment_date" name="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="modal_receipt" class="form-label">Payment Receipt</label>
                            <input type="file" class="form-control" id="modal_receipt" name="receipt" required>
                        </div>

                        <div class="mb-3">
                            <label for="modal_notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="modal_notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ✅ FIX: Added jQuery library before other scripts that use it. --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // PDF Export Logic
            $('#export-pdf-btn').on('click', function () {
                const element = document.getElementById('printableArea');
                const options = { margin: 0.5, filename: 'tenant_details.pdf', image: { type: 'jpeg', quality: 0.98 }, html2canvas: { scale: 2, useCORS: true }, jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }};
                html2pdf().set(options).from(element).save();
            });

            // Modal Logic for Passing Data
            $('#paymentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var installmentId = button.data('installment-id');
                var amount = button.data('amount');

                var modal = $(this);
                modal.find('#modal_installment_id').val(installmentId);
                modal.find('#modal_total_amount').val(amount);
            });

            // Logic for showing/hiding the partial payment field
            $('#payment_type').on('change', function() {
                if ($(this).val() === 'partial') {
                    $('#partial_amount_div').show();
                    $('#modal_partial_amount').prop('required', true);
                } else {
                    $('#partial_amount_div').hide();
                    $('#modal_partial_amount').prop('required', false).val('');
                }
            });
        });
    </script>
@endpush
