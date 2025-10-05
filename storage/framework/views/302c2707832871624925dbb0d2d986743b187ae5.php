<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Buyer Create')); ?>

<?php $__env->stopSection(); ?>

<style>
    .dz-preview .dz-remove {
        font-size: 1.5rem;
        color: #fff;
        text-decoration: none;
        background: rgb(255, 0, 0);
        width: 100%;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        line-height: 1;
        opacity: 1;
        z-index: 4444;
        /* Ensure it's on top */
    }
</style>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>
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
                // ✅ FIX: These options enable the remove button functionality
                addRemoveLinks: true,
                dictRemoveFile: 'delete',
                thumbnail: function(file, dataUrl) {
                    if (file.previewElement) {
                        file.previewElement.classList.remove("dz-file-preview");
                        var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                        for (var i = 0; i < images.length; i++) {
                            var thumbnailElement = images[i];
                            thumbnailElement.alt = file.name;
                            thumbnailElement.src = dataUrl;
                        }
                        setTimeout(function() {
                            file.previewElement.classList.add("dz-image-preview");
                        }, 1);
                    }
                }
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
                $.ajax({
                    url: "<?php echo e(route('tenant.store')); ?>",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: fd,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        $('#tenant-submit').attr('disabled', false);
                        if (data.status == "success") {
                            toastrs('Success', data.msg, 'success');
                            setTimeout(() => {
                                window.location.href = "<?php echo e(route('tenant.index')); ?>";
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

            // ✅ FIX: Dependent Dropdown now uses your existing 'property.unit' route
            $('#property').on('change', function() {
                var propertyId = $(this).val();
                var unitDropdown = $('#unit');

                unitDropdown.html('<option value=""><?php echo e(__('Loading...')); ?></option>');
                unitDropdown.prop('disabled', true);

                if (propertyId) {
                    // Construct the correct URL using the existing route
                    var url = '<?php echo e(route('property.unit', ':id')); ?>';
                    url = url.replace(':id', propertyId);

                    $.ajax({
                        url: url,
                        type: 'GET', // Your route uses GET
                        dataType: 'json',
                        success: function(data) {
                            unitDropdown.html(
                                '<option value=""><?php echo e(__('Select Unit')); ?></option>');
                            unitDropdown.prop('disabled', false);

                            if (data && !$.isEmptyObject(data)) {
                                $.each(data, function(id, name) {
                                    unitDropdown.append('<option value="' + id + '">' +
                                        name + '</option>');
                                });
                            } else {
                                unitDropdown.html(
                                    '<option value=""><?php echo e(__('No Available Units')); ?></option>'
                                );
                                unitDropdown.prop('disabled', true);
                            }
                        },
                        error: function() {
                            unitDropdown.html(
                                '<option value=""><?php echo e(__('Could not load units')); ?></option>'
                            );
                            unitDropdown.prop('disabled', true);
                        }
                    });
                } else {
                    unitDropdown.html('<option value=""><?php echo e(__('Select Property First')); ?></option>');
                    unitDropdown.prop('disabled', true);
                }
            });

            // --- Installment Fields Logic ---
            function calculateEndDate() {
                let startDateStr = $('#installment_start_date').val();
                let duration = parseInt($('#installment_duration').val());
                let type = $('#installment_type').val();
                if (!startDateStr || !duration || duration <= 0 || !type) {
                    $('#installment_end_date').val('');
                    return;
                }
                let startDate = new Date(startDateStr);
                if (isNaN(startDate)) {
                    $('#installment_end_date').val('');
                    return;
                }
                let endDate = new Date(startDate.getTime());
                let monthsToAdd = 0;
                switch (type) {
                    case 'monthly':
                        monthsToAdd = duration;
                        break;
                    case 'quarter_year':
                        monthsToAdd = duration * 3;
                        break;
                    case 'half_year':
                        monthsToAdd = duration * 6;
                        break;
                    case 'yearly':
                        monthsToAdd = duration * 12;
                        break;
                }
                endDate.setMonth(endDate.getMonth() + monthsToAdd);
                let year = endDate.getFullYear();
                let month = (endDate.getMonth() + 1).toString().padStart(2, '0');
                let day = endDate.getDate().toString().padStart(2, '0');
                $('#installment_end_date').val(`${year}-${month}-${day}`);
            }

            function calculateInstallmentAmount() {
                let unitPrice = parseFloat($('#unit_price').val());
                let deposit = parseFloat($('#deposit').val());
                let duration = parseInt($('#installment_duration').val());
                let feePercent = parseFloat($('#installment_fee_percent').val());
                let purchaseType = $('#purchase_type').val();

                if (purchaseType !== 'installment') {
                    $('.purchase_installment').addClass('d-none');
                    $('#installment_amount, #price_after_deposit').val('');
                    return;
                }

                $('.purchase_installment').removeClass('d-none');

                if (isNaN(unitPrice) || isNaN(deposit) || isNaN(duration) || duration <= 0) {
                    $('#installment_amount, #price_after_deposit').val('');
                    return;
                }

                let priceAfterDeposit = unitPrice - deposit;
                if (priceAfterDeposit < 0) priceAfterDeposit = 0;
                $('#price_after_deposit').val(priceAfterDeposit.toFixed(2));

                let totalInstallmentAmount = priceAfterDeposit;
                if (!isNaN(feePercent) && feePercent > 0) {
                    totalInstallmentAmount += priceAfterDeposit * (feePercent / 100);
                }

                let installmentAmount = totalInstallmentAmount / duration;
                $('#installment_amount').val(installmentAmount.toFixed(2));
            }

            $('#purchase_type, #unit_price, #deposit, #installment_duration, #installment_fee_percent, #installment_start_date, #installment_type')
                .on('change keyup', function() {
                    calculateInstallmentAmount();
                    calculateEndDate();
                });

            calculateInstallmentAmount();
            calculateEndDate();
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Buyer')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Create')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo e(Form::open(['url' => 'tenant', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form'])); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Personal Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('first_name', __('First Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __('Enter First Name'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('last_name', __('Last Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Last Name'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('password', __('Password'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('phone_number', __('Phone Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('family_member', __('Total Family Member'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('national_id', __('National ID'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('national_id', null, ['class' => 'form-control', 'placeholder' => __('Enter National ID')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('profile', __('Profile'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::file('profile', ['class' => 'form-control'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Address Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('country', __('Country'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City'), 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required'])); ?>

                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Enter Address'), 'required'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Property Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('property', __('Property'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::select('property', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property', 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('unit', __('Unit'), ['class' => 'form-label'])); ?>

                                <select class="form-control hidesearch" id="unit" name="unit" required disabled>
                                    <option value=""><?php echo e(__('Select Property First')); ?></option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('unit_price', __('Unit Price'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('unit_price', null, ['class' => 'form-control', 'id' => 'unit_price', 'min' => 0, 'step' => '0.01', 'required'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('purchase_type', __('Purchase Type'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::select('purchase_type', ['full' => 'Full Payment', 'installment' => 'Installment'], null, ['class' => 'form-control', 'id' => 'purchase_type'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_full d-none">
                                <?php echo e(Form::label('payment_date', __('Payment Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('payment_date', null, ['class' => 'form-control', 'placeholder' => __('Enter payment date')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_type', __('Installment Type'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::select('installment_type', ['monthly' => __('Monthly'), 'quarter_year' => __('Quarter Year (3 Months)'), 'half_year' => __('Half Year (6 Months)'), 'yearly' => __('Yearly')], null, ['class' => 'form-control', 'id' => 'installment_type'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_duration', __('Installment Duration'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_duration', null, ['class' => 'form-control', 'id' => 'installment_duration', 'min' => 1])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('installment_start_date', null, ['class' => 'form-control', 'id' => 'installment_start_date'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('installment_end_date', null, ['class' => 'form-control', 'id' => 'installment_end_date', 'readonly'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('deposit', __('Deposit'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('deposit', null, ['class' => 'form-control', 'id' => 'deposit', 'min' => 0, 'step' => '0.01'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('price_after_deposit', __('Price After Deposit'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('price_after_deposit', null, ['class' => 'form-control', 'id' => 'price_after_deposit', 'readonly'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_fee_percent', __('Installment Fee %'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_fee_percent', null, ['class' => 'form-control', 'id' => 'installment_fee_percent', 'step' => '0.01', 'min' => 0])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_amount', __('Installment Amount'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_amount', null, ['class' => 'form-control', 'id' => 'installment_amount', 'readonly'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Documents')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="dropzone needsclick" id='demo-upload' action="#">
                        <div class="dz-message needsclick">
                            <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                            <h3><?php echo e(__('Drop files here or click to upload.')); ?></h3>
                        </div>
                    </div>
                    
                    <div class="preview-dropzon" style="display: none;">
                        <div class="dz-preview dz-file-preview">
                            <div class="dz-image"><img data-dz-thumbnail="" src="" alt=""></div>
                            <div class="dz-details">
                                <div class="dz-size"><span data-dz-size=""></span></div>
                                <div class="dz-filename"><span data-dz-name=""></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""> </span></div>
                            <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="group-button text-end">
                <?php echo e(Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'tenant-submit'])); ?>

            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/tenant/create.blade.php ENDPATH**/ ?>