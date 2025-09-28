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
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a></li>
        <li class="breadcrumb-item"><a href="{{ route('tenant.index') }}">{{ __('Tenant') }}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{ __('Details') }}</a></li>
    </ul>
@endsection

@section('card-action-btn')
    <a href="{{ route('pdf.download', ['type' => 'tenant', 'id' => $tenant->id]) }}" target="_blank"
        class="btn btn-primary btn-sm">
        <i data-feather="download" class="me-1" style="width:16px; height:16px;"></i>
        {{ __('Export as PDF') }}
    </a>
@endsection


@section('styles')
    <style>
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
@endsection

@section('content')
    {{-- Session Alert Messages --}}


    {{-- Main Content --}}
    <div class="row">
        {{-- Left Card: User Profile --}}
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="card user-card border-0 shadow-sm">
                <div class="card-header bg-transparent" style="min-height: 50px;"></div>
                <div class="card-body text-center">
                    <div class="user-imgwrap"><img class="img-fluid rounded-circle"
                            src="{{ optional($tenant->user)->profile ? Storage::url($tenant->user->profile) : asset('path/to/default/avatar.png') }}"
                            alt="Profile Image"></div>
                    <div class="user-detailwrap">
                        <h3>{{ optional($tenant->user)->first_name }} {{ optional($tenant->user)->last_name }}</h3>
                        <h6 class="text-muted">{{ optional($tenant->user)->email ?? '-' }}</h6>
                        <h6 class="text-muted">{{ optional($tenant->user)->phone_number ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Information Cards --}}
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h4>{{ __('Financial Summary') }}</h4>
                </div>
                <div class="card-body financial-summary">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-primary"><i data-feather="dollar-sign"></i></div>
                                <h6>Total Amount</h6>
                                <p>{{ number_format($financialSummary['total_amount'], 2) }} EGP</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-success"><i data-feather="check-circle"></i></div>
                                <h6>Amount Paid</h6>
                                <p>{{ number_format($financialSummary['paid_amount'], 2) }} EGP</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-danger"><i data-feather="trending-down"></i></div>
                                <h6>Amount Due</h6>
                                <p>{{ number_format($financialSummary['due_amount'], 2) }} EGP</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm info-card">
                <div class="card-header bg-transparent">
                    <h4>{{ __('Additional Information') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('Property') }}</h6>
                                    <p>{{ optional($tenant->linked_property)->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('Unit') }}</h6>
                                    <p>{{ optional($tenant->propertyUnit)->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('Total Family Member') }}</h6>
                                    <p>{{ $tenant->family_member ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('Country') }}</h6>
                                    <p>{{ $tenant->country ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('State') }}</h6>
                                    <p>{{ $tenant->state ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('City') }}</h6>
                                    <p>{{ $tenant->city ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6>{{ __('Zip Code') }}</h6>
                                    <p>{{ $tenant->zip_code ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        @if ($tenant->installments->isNotEmpty())
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="media">
                                    <div class="media-body">
                                        <h6>{{ __('Lease Start Date') }}</h6>
                                        <p>{{ \Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="media">
                                    <div class="media-body">
                                        <h6>{{ __('Lease End Date') }}</h6>
                                        <p>{{ \Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Installment Plan Card --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h4>{{ __('Installment Plan') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table installments-table" id="invoice-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- ✅ FIX: Split Due Date into three columns --}}
                                    <th>{{ __('Due Date') }}</th>

                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ✅ FIX: Removed filter to show all installments, sorted by due date --}}
                                @forelse($tenant->installments->sortBy('due_date') as $installment)
                                    <tr>
                                        <td>{{ $installment->installment_number }}</td>
                                        {{-- ✅ FIX: Display day, month, and year in separate columns --}}
                                        <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m') }} </td>
                                        <td>{{ number_format($installment->amount, 2) }} EGP</td>
                                        <td class="text-center">
                                            @if ($installment->status == 'paid')
                                                <span class="badge bg-success text-white">Paid</span>
                                            @elseif($installment->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger text-white">Overdue</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($installment->status != 'paid')
                                                <a href="#" class="btn btn-sm btn-outline-success customModal"
                                                    data-url="{{ route('installment.payment.create', $installment->id) }}"
                                                    data-title="Record Payment for Installment #{{ $installment->installment_number }}"
                                                    data-size="md">
                                                    Record Payment
                                                </a>
                                            @else
                                                @if (optional($installment->invoice)->payment?->receipt)
                                                    <a href="{{ Storage::url($installment->invoice->payment->receipt) }}"
                                                        class="btn btn-sm btn-outline-primary" download>
                                                        <i data-feather="download" style="width:16px; height:16px;"></i>
                                                        Download Receipt
                                                    </a>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- ✅ FIX: Updated colspan to match new number of columns --}}
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-muted mb-0">No installment plan found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Generic modal container for the customModal system --}}
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="commonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Content will be loaded here by JavaScript --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click', '.customModal', function() {
            var modal = $('#commonModal');
            var url = $(this).data('url');
            var title = $(this).data('title');
            var size = $(this).data('size') || 'md';

            modal.find('.modal-title').text(title);
            modal.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl').addClass('modal-' + size);

            $.get(url, function(data) {
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });
    </script>
@endpush
