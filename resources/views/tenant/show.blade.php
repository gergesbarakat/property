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



@section('content')
    \
    <style>
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

        /* ✅ NEW: Custom styles for the new payment modal */
        .installment_modal_backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1050;
            /* High z-index */
            display: none;
        }

        .installment_modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 500px;
            background-color: #fff;
            border-radius: .5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            z-index: 1051;
            /* Higher than backdrop */
            display: none;
            flex-direction: column;
            max-height: 90vh;
        }

        .installment_modal .modal-header,
        .installment_modal .modal-body,
        .installment_modal .modal-footer {
            padding: 1rem;
        }

        .installment_modal .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
        }

        .installment_modal .modal-body {
            overflow-y: auto;
        }

        .installment_modal .modal-footer {
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .installment_modal .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            padding: 0;
            opacity: 0.7;
        }
    </style>
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
                            src="{{ optional($tenant->user)->profile ? Storage::url($tenant->user->profile) : asset('path/to/default/avatar.png') }}"
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
                                                {{-- ✅ This button now triggers our new custom popup --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-success open-installment-modal"
                                                    data-installment-id="{{ $installment->id }}"
                                                    data-amount="{{ $installment->amount }}">
                                                    Record Payment
                                                </button>
                                            @else
                                                <span>-</span>
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

    {{-- ✅ NEW: Custom Payment Popup (Replaces Bootstrap Modal) --}}
    <div class="installment_modal_backdrop" id="installment_modal_backdrop"></div>
    <div class="installment_modal" id="installment_modal">
        <div class="modal-header">
            <h5 class="modal-title">Record Payment & Upload Receipt</h5>
            <button type="button" class="btn-close close-installment-modal">&times;</button>
        </div>
        <form id="installment_payment_form" enctype="multipart/form-data">
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
                    <input type="number" class="form-control" id="modal_partial_amount" name="partial_amount"
                        step="0.01">
                </div>

                <div class="mb-3">
                    <label for="modal_payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="modal_payment_date" name="payment_date"
                        value="{{ now()->format('Y-m-d') }}" required>
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
                <button type="button" class="btn btn-secondary close-installment-modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Payment</button>
            </div>
        </form>
    </div>

    <script>
        // Use a no-conflict wrapper to ensure '$' works with your theme's jQuery.
        (function($) {
            "use strict";

            $(document).ready(function() {
                var modal = $('#installment_modal');
                var backdrop = $('#installment_modal_backdrop');

                // ✅ FIX: The AJAX submission logic has been removed.
                // The script now only handles opening and closing the modal.

                // --- Open Modal ---
                $('.open-installment-modal').on('click', function() {
                    var button = $(this);
                    var installmentId = button.data('installment-id');
                    var amount = button.data('amount');

                    modal.find('#modal_installment_id').val(installmentId);
                    modal.find('#modal_total_amount').val(amount);

                    backdrop.fadeIn(200);
                    modal.css('display', 'flex').hide().fadeIn(200);
                });

                // --- Close Modal ---
                function closeModal() {
                    backdrop.fadeOut(200);
                    modal.fadeOut(200);
                    // Reset the form for the next time it opens
                    modal.find('form')[0].reset();
                    $('#partial_amount_div').hide();
                    $('#payment_type').val('full');
                }
                $('.close-installment-modal').on('click', closeModal);
                backdrop.on('click', closeModal);

                // --- Toggle Partial Payment Input ---
                $('#payment_type').on('change', function() {
                    if ($(this).val() === 'partial') {
                        $('#partial_amount_div').slideDown();
                        $('#modal_partial_amount').prop('required', true);
                    } else {
                        $('#partial_amount_div').slideUp();
                        $('#modal_partial_amount').prop('required', false).val('');
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
