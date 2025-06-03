@extends('layouts.app')
@section('page-title')
    {{ __('Property Details') }}
@endsection
@section('page-class')
    product-detail-page
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('property.index') }}">{{ __('Property') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Details') }}</a>
        </li>
    </ul>
@endsection
@section('content')
    @can('create unit')
        <div class="row">
            <div class="col-sm-12 text-end">
                <a href="#" class="btn btn-primary btn-sm customModal" data-title="{{ __('Add Unit') }}"
                    data-url="{{ route('unit.create', $property->id) }}" data-size="lg"> <i
                        class="ti-plus mr-5"></i>{{ __('Add Unit') }}</a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-md-5 cdx-xl-45">
            <div class="product-card">
                <div class="product-for">
                    @foreach ($property->propertyImages as $image)
                        @if (!empty($image) && !empty($image->image))
                            @php  $img= $image->image; @endphp
                        @else
                            @php  $img= 'default.jpg'; @endphp
                        @endif
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="{{ asset(Storage::url('upload/property')) . '/' . $img }}"
                                    alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="product-to">
                    @foreach ($property->propertyImages as $image)
                        @if (!empty($image) && !empty($image->image))
                            @php  $img= $image->image; @endphp
                        @else
                            @php  $img= 'default.jpg'; @endphp
                        @endif
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="{{ asset(Storage::url('upload/property')) . '/' . $img }}"
                                    alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-7 cdx-xl-55 cdxpro-detail">
            <div class="product-card">
                <div class="detail-group">
                    <div class="media">
                        <div>
                            <h2>{{ $property->name }}</h2>
                            <h6 class="text-light">
                                <div class="date-info">
                                    <span class="badge badge-primary" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Type') }}">{{ \App\Models\Property::$Type[$property->type] }}</span>
                                </div>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="detail-group">
                    <h6>{{ __('Property Details') }}</h6>
                    <p class="mb-10">{{ $property->description }}</p>

                </div>
                <div class="detail-group">
                    <h6>{{ __('Property Address') }}</h6>
                    <p class="mb-10">{{ $property->address }}</p>
                    <p class="mb-10">{{ $property->city . ', ' . $property->state . ', ' . $property->country }}</p>
                    <p class="mb-10">{{ $property->zip_code }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-4 bg-white p-30 rounded">
        <div class="p-2 h4">Property Units</div>
        <table class="table table-bordered   align-middle" id='invoice-table'>
            <thead class="table-light">
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
                                            data-url="{{ route('unit.edit', [$unit->property_id, $unit->id]) }}" href="#"
                                            data-size="lg" data-title="{{ __('Edit Unit') }}" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Edit') }}">
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
@endsection
