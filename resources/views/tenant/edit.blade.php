@extends('layouts.app')
@section('page-title')
    {{ __('Buyer Edit') }}
@endsection
@push('script-page')
    <script src="{{ asset('assets/js/vendors/dropzone/dropzone.js') }}"></script>
    <script>
        $(function() {
            "use strict";

            // --- Dropzone Initialization ---
            Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone('#demo-upload', {
                previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
                parallelUploads: 10,
                thumbnailHeight: 120,
                thumbnailWidth: 120,
                maxFilesize: 10,
                filesizeBase: 1000,
                autoProcessQueue: false,
                addRemoveLinks: true,
                dictRemoveFile: 'delete',
            });

            // --- AJAX Form Submission ---
            $('#tenant-submit').on('click', function(e) {
                e.preventDefault();
                $('#tenant-submit').attr('disabled', true);

                var fd = new FormData($('#tenant_form')[0]);
                var files = myDropzone.getAcceptedFiles();
                $.each(files, function(key, file) {
                    fd.append('contracts[]', file, file.name);
                });

                // Add the PUT method override for AJAX submission
                fd.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('tenant.update', $tenant->id) }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: fd,
                    contentType: false,
                    processData: false,
                    type: 'POST', // Use POST and override with _method
                    success: function(data) {
                        $('#tenant-submit').attr('disabled', false);
                        if (data.status == "success") {
                            toastrs('Success', data.msg, 'success');
                            setTimeout(() => {
                                window.location.href = "{{ route('tenant.index') }}";
                            }, 1000);
                        } else {
                            toastrs('Error', data.msg, 'error');
                        }
                    },
                    error: function(data) {
                        $('#tenant-submit').attr('disabled', false);
                        let errorMessage = 'An unexpected error occurred.';
                        if (data.responseJSON && data.responseJSON.errors) {
                            let errorList = [];
                            $.each(data.responseJSON.errors, function(key, value) {
                                errorList.push(value[0]);
                            });
                            errorMessage = errorList.join('<br>');
                        } else if (data.responseJSON && data.responseJSON.msg) {
                            errorMessage = data.responseJSON.msg;
                        }
                        toastrs('Error', errorMessage, 'error');
                    },
                });
            });
        });
    </script>
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a></li>
        <li class="breadcrumb-item"><a href="{{ route('tenant.index') }}">{{ __('Buyer') }}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{ __('Edit') }}</a></li>
    </ul>
@endsection
@section('styles')
    <style>
        /* ✅ NEW: Styles for the remove button on the Dropzone preview */
        .dz-preview .dz-remove {
            font-size: 1.5rem;
            color: #fff;
            position: absolute;
            top: 5px;
            right: 5px;
            text-decoration: none;
            background: rgba(0, 0, 0, 0.5);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            line-height: 1;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        .dz-preview:hover .dz-remove {
            opacity: 1;
        }

        .dz-preview .dz-remove:hover {
            background-color: rgba(255, 0, 0, 0.8);
        }
    </style>
@endsection
@section('content')
    {{ Form::model($tenant, ['route' => ['tenant.update', $tenant->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form']) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Personal Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('first_name', __('First Name'), ['class' => 'form-label']) }}
                                {{ Form::text('first_name', $user->first_name, ['class' => 'form-control', 'placeholder' => __('Enter First Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('last_name', __('Last Name'), ['class' => 'form-label']) }}
                                {{ Form::text('last_name', $user->last_name, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                {{ Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) }}
                                {{ Form::text('phone_number', $user->phone_number, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('national_id', __('National ID'), ['class' => 'form-label']) }}
                                {{ Form::text('national_id', null, ['class' => 'form-control', 'placeholder' => __('Enter National ID')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('family_member', __('Total Family Member'), ['class' => 'form-label']) }}
                                {{ Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')]) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('profile', __('Profile'), ['class' => 'form-label']) }}
                                {{ Form::file('profile', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Address Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}
                                {{ Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}
                            </div>
                            <div class="form-group ">
                                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Enter Address')]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Property Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <h6>{{ __('Property') }}</h6>
                                <p class="form-control-static">{{ optional($tenant->linked_property)->name ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-md-6">
                                <h6>{{ __('Unit') }}</h6>
                                <p class="form-control-static">{{ optional($tenant->propertyUnit)->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Documents') }}</h5>
                </div>
                <div class="card-body">
                    <h5>{{ __('Existing Documents') }}</h5>
                    @if ($tenant->contracts->isNotEmpty())
                        <ul class="list-group mb-4">
                            @foreach ($tenant->contracts as $contract)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ Storage::url($contract->contract_file) }}"
                                        target="_blank">{{ basename($contract->contract_file) }}</a>
                                    <form action="{{ route('tenant.contract.destroy', $contract->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No documents have been uploaded yet.</p>
                    @endif
                    <hr>
                    <h5 class="mt-4">{{ __('Upload New Documents') }}</h5>
                    <div class="dropzone needsclick" id='demo-upload' action="#">
                        <div class="dz-message needsclick">
                            <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                            <h3>{{ __('Drop new files here or click to upload.') }}</h3>
                        </div>
                    </div>
                    {{-- ✅ FIX: The preview template now includes a remove link --}}
                    <div class="preview-dropzon" style="display: none;">
                        <div class="dz-preview dz-file-preview">
                            <div class="dz-image"><img data-dz-thumbnail="" src="" alt=""></div>
                            <div class="dz-details">
                                <div class="dz-size"><span data-dz-size=""></span></div>
                                <div class="dz-filename"><span data-dz-name=""></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""> </span></div>
                            <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
                            <a href="#!" class="dz-remove" data-dz-remove></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="group-button text-end">
                {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'tenant-submit']) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
