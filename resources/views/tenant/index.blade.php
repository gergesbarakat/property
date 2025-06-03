@extends('layouts.app')
@section('page-title')
    {{ __('Tenant') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Tenant') }}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create tenant')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('tenant.create') }}" data-size="md"> <i
                class="ti-plus mr-5"></i>{{ __('Create Tenant') }}</a>
    @endcan
@endsection
@section('content')
    <div class="row">
        @foreach ($tenants as $tenant)
            <div class="w-full p-10 bg-white">
                <div class="  p-10">
                    <table class="table table-bordered table-striped" id="invoice-table">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Family Member</th>
                                <th>Property</th>
                                <th>Unit</th>
                                <th>Lease Start Date</th>
                                <th>Lease End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <img class="rounded-circle" width="50" height="50"
                                         src="{{ (!empty($tenant->user) && !empty($tenant->user->profile))
                                             ? asset(Storage::url("upload/profile/" . $tenant->user->profile))
                                             : asset(Storage::url("upload/profile/avatar.png")) }}"
                                         alt="Profile">
                                </td>
                                <td>{{ ucfirst(optional($tenant->user)->first_name) }} {{ ucfirst(optional($tenant->user)->last_name) }}</td>
                                <td>{{ optional($tenant->user)->email ?? '-' }}</td>
                                <td>{{ optional($tenant->user)->phone_number ?? '-' }}</td>
                                <td>{{ $tenant->address }}</td>
                                <td>{{ $tenant->family_member }}</td>
                                <td>{{ optional($tenant->properties)->name ?? '-' }}</td>
                                <td>{{ optional($tenant->units)->name ?? '-' }}</td>
                                <td>{{ dateFormat($tenant->lease_start_date) }}</td>
                                <td>{{ dateFormat($tenant->lease_end_date) }}</td>
                                <td>
                                    <div class="btn-group">
                                        @can('show tenant')
                                            <a href="{{ route('tenant.show', $tenant->id) }}" class="btn btn-sm btn-primary" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit tenant')
                                            <a href="{{ route('tenant.edit', $tenant->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete tenant')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['tenant.destroy', $tenant->id], 'style' => 'display:inline']) !!}
                                            <button type="submit" class="btn btn-sm btn-danger confirm_dialog" title="Delete">
                                                <i data-feather="trash"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        @endforeach
    </div>
@endsection
