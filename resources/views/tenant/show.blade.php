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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .user-card .card-body {
            margin-top: 60px;
        }

        .media-body h6 {
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .media-body p {
            font-weight: 500;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
        }
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

    {{-- Main Content --}}
    <div class="row">
        {{-- Left Card: User Profile --}}
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="card user-card">
                <div class="card-header" style="min-height: 50px;"></div>
                <div class="card-body text-center">
                    <div class="user-imgwrap"><img class="img-fluid rounded-circle"
                            src="{{ asset(Storage::url('upload/profiles')) . '/' . $tenant->user->profile }}"
                            alt="Profile Image"></div>
                    <div class="user-detailwrap">
                        <h3>{{ optional($tenant->user)->first_name }} {{ optional($tenant->user)->last_name }}</h3>
                        <h6>{{ optional($tenant->user)->email ?? '-' }}</h6>
                        <h6>{{ optional($tenant->user)->phone_number ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Card: Additional Information --}}
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Additional Information') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Installment Plan Card --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Installment Plan') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="installments-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenant->installments as $installment)
                                    <tr>
                                        <td>{{ $installment->installment_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('F j, Y') }}</td>
                                        <td>${{ number_format($installment->amount, 2) }}</td>
                                        <td class="text-center">
                                            @if ($installment->status == 'paid')
                                                <span class="badge bg-success text-white">Paid</span>
                                            @elseif($installment->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>@else<span
                                                    class="badge bg-danger text-white">Overdue</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($installment->status != 'paid')
                                                {{-- ✅ FIX: The button now uses your theme's customModal system --}}
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
                                        <td colspan="5" class="text-center py-4">
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

    {{-- ✅ NEW: Generic modal container for the customModal system --}}
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

    {{-- ✅ NEW: JavaScript to power your theme's customModal buttons --}}
    <script>
        $(document).on('click', '.customModal', function() {
            var modal = $('#commonModal');
            var url = $(this).data('url');
            var title = $(this).data('title');
            var size = $(this).data('size') || 'md'; // Default to medium size if not specified

            // Set modal title and size
            modal.find('.modal-title').text(title);
            modal.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl').addClass('modal-' + size);

            // Fetch content from the URL and inject it into the modal body
            $.get(url, function(data) {
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });
    </script>
@endpush
