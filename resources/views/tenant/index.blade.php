@extends('layouts.app')
@section('page-title')
    {{ __('Buyer') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Buyer') }}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create tenant')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('tenant.create') }}" data-size="md"> <i
                class="ti-plus mr-5"></i>{{ __('Create Buyer') }}</a>
    @endcan
@endsection



@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-header  p-20 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('All Buyers') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table modern-table" id="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">{{ __('Buyer') }}</th>
                            <th style="width: 15%;">{{ __('Property') }}</th>
                            <th style="width: 15%;">{{ __('Unit') }}</th>
                            <th style="width: 12%;">{{ __('Contract Start') }}</th>
                            <th style="width: 12%;">{{ __('Contract End') }}</th>
                            <th style="width: 11%;">{{ __('Purchase Type') }}</th>
                            {{-- ✅ FIX: Aligned the Actions header to the end (right in LTR, left in RTL) --}}
                            <th style="width: 10%;" class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong class="d-block">{{ optional($tenant->user)->first_name }}
                                                {{ optional($tenant->user)->last_name }}</strong>
                                            <small class="text-muted">{{ optional($tenant->user)->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($tenant->linked_property)->name ?? '-' }}</td>
                                <td>{{ optional($tenant->propertyUnit)->name ?? '-' }}</td>
                                <td class="whitespace-nowrap">
                                    @if ($tenant->installments->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('M j, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    @if ($tenant->installments->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('M j, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ucfirst($tenant->purchase_type) }}</td>
                                {{-- ✅ FIX: Aligned the action buttons to the end --}}
                                <td class="text-end action-buttons">
                                    <a href="{{ route('tenant.show', $tenant->id) }}"
                                        class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="View"><i
                                            data-feather="eye"></i></a>
                                    @can('edit tenant')
                                        <a href="{{ route('tenant.edit', $tenant->id) }}"
                                            class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Edit"><i
                                                data-feather="edit"></i></a>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h5 class="text-muted">No buyers found.</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
