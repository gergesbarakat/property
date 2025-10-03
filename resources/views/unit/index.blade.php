@extends('layouts.app')
@section('page-title')
    {{ __('Units') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Units') }}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance" id="invoice-table">
                        <thead>
                            <tr>
                                <th>{{ __('Property') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Bedroom') }}</th>
                                <th>{{ __('Kitchen') }}</th>
                                <th>{{ __('Bath') }}</th>
                                <th>{{ __('Unit Size') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('edit unit') || Gate::check('delete unit'))
                                    <th class="text-center">{{ __('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr>
                                    {{-- ✅ FIX: Use the optional() helper to safely access the property name. --}}
                                    <td>{{ optional($unit->property)->name ?? '-' }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->bedroom }}</td>
                                    <td>{{ $unit->kitchen }}</td>
                                    <td>{{ $unit->baths }}</td>
                                    <td>{{ $unit->unit_size ? $unit->unit_size . ' Sq. Ft.' : '-' }}</td>
                                    <td>
                                        @if (strtolower($unit->status) != 'available')
                                            <span class="badge bg-danger text-white">{{ ucfirst($unit->status) }}</span>
                                        @else
                                            <span class="badge bg-success text-white">{{ ucfirst($unit->status) }}</span>
                                        @endif
                                    </td>
                                    @if (Gate::check('edit unit') || Gate::check('delete unit'))
                                        <td class="text-center">
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['unit.destroy', [$unit->property_id, $unit->id]]]) !!}
                                                @can('edit unit')
                                                    <a class="text-success customModal"
                                                        data-url="{{ route('unit.edit', [$unit->property_id, $unit->id]) }}"
                                                        href="#" data-size="lg" data-title="{{ __('Edit Unit') }}"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
                                                        <i data-feather="edit"></i></a>
                                                @endcan
                                                @can('delete unit')
                                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Delete') }}" href="#"> <i
                                                            data-feather="trash-2"></i></a>
                                                @endcan
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
