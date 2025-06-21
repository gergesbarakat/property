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
            <h4 class="mb-0">All Buyers</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table modern-table" id="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Buyer</th>
                            <th>Property</th>
                            <th>Unit</th>
                            <th>Contract Start</th>
                            <th>Contract End</th>
                            <th>purchase_type</th>

                            <th>created_at</th>

                            <th>updated_at</th>



                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center">
                                        @if (optional($tenant->user)->profile)
                                            <img class="rounded-circle avatar"
                                                src="{{ Storage::url($tenant->user->profile) }}" alt="Profile">
                                        @else
                                            <div
                                                class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary">
                                                <span
                                                    style="font-size: 1rem; font-weight: 600;">{{ substr(optional($tenant->user)->first_name, 0, 1) }}{{ substr(optional($tenant->user)->last_name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="ms-3">
                                            <strong class="d-block">{{ optional($tenant->user)->first_name }}
                                                {{ optional($tenant->user)->last_name }}</strong>
                                            <small class="text-muted">{{ optional($tenant->user)->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($tenant->linked_property)->name ?? '-' }}</td>
                                <td>{{ optional($tenant->propertyUnit)->name ?? '-' }}</td>
                                <td>
                                    @if ($tenant->installments->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('M j, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($tenant->installments->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('M j, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $tenant->purchase_type }}</td>
                                <td>{{ $tenant->created_at }}</td>
                                <td>{{ $tenant->updated_at }}</td>
                                <td class="text-center action-buttons">
                                    {{-- ✅ Buttons updated with distinct colors --}}
                                    <a href="{{ route('tenant.show', $tenant->id) }}"
                                        class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="View"><i
                                            data-feather="eye"></i></a>
                                    <a href="{{ route('tenant.edit', $tenant->id) }}"
                                        class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Edit"><i
                                            data-feather="edit"></i></a>
                                    <form action="{{ route('tenant.destroy', $tenant->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white"
                                            data-bs-toggle="tooltip" title="Delete"
                                            onclick="return confirm('Are you sure?')"><i data-feather="trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
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
