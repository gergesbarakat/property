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
                                {{-- <th>{{ __('Rent Type') }}</th>
                                <th>{{ __('Rent') }}</th> --}}
                                {{-- <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Payment Due Date') }}</th>
                                <th>{{ __('Rent Duration') }}</th>
                                <th>{{ __('Deposit Type') }}</th>
                                <th>{{ __('Deposit Amount') }}</th>
                                <th>{{ __('Late Fee Type') }}</th>
                                <th>{{ __('Late Fee Amount') }}</th>
                                <th>{{ __('Incident Receipt Amount') }}</th> --}}
                                <th>{{ __('Created ') }}</th>
                                <th>{{ __('Updated') }}</th>

                                @if (Gate::check('edit unit') || Gate::check('delete unit'))
                                    <th class="text-center">{{ __('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr>
                                    <td>{{ $unit->properties->name }}</td>

                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->bedroom }}</td>
                                    <td>{{ $unit->kitchen }}</td>
                                    <td>{{ $unit->baths }}</td>
                                    {{-- <td>{{ $unit->rent_type }}</td>
                                <td>{{ priceFormat($unit->rent) }}</td>
                                <td>{{ $unit->rent_type === 'custom' ? dateFormat($unit->start_date) : '-' }}</td>
                                <td>{{ $unit->rent_type === 'custom' ? dateFormat($unit->end_date) : '-' }}</td>
                                <td>{{ $unit->rent_type === 'custom' ? dateFormat($unit->payment_due_date) : '-' }}</td>
                                <td>{{ $unit->rent_type !== 'custom' ? $unit->rent_duration : '-' }}</td>
                                <td>{{ $unit->deposit_type }}</td>
                                <td>
                                    {{ $unit->deposit_type == 'fixed' ? priceFormat($unit->deposit_amount) : $unit->deposit_amount . '%' }}
                                </td>
                                <td>{{ $unit->late_fee_type }}</td>
                                <td>
                                    {{ $unit->late_fee_type == 'fixed' ? priceFormat($unit->late_fee_amount) : $unit->late_fee_amount . '%' }}
                                </td>
                                <td>{{ priceFormat($unit->incident_receipt_amount) }}</td> --}}
                                    <td>{{ $unit->created_at }}</td>
                                    <td>{{ $unit->updated_at }}</td>

                                    @if (Gate::check('edit unit') || Gate::check('delete unit'))
                                        <td class="text-right">
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
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
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
