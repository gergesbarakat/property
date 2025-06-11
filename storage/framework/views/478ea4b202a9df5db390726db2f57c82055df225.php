<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Buyer Create')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>
    <script>
        var dropzone = new Dropzone('#demo-upload', {
            previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 10,
            filesizeBase: 1000,
            autoProcessQueue: false,
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
        $('#tenant-submit').on('click', function() {
            "use strict";
            $('#tenant-submit').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('profile').files[0];

            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('tenant_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            fd.append('profile', file);
            var other_data = $('#tenant_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
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
                    if (data.status == "success") {
                        $('#tenant-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '<?php echo e(route('tenant.index')); ?>';
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#tenant-submit').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#tenant-submit').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });

        $('#property').on('change', function() {
            "use strict";
            var property_id = $(this).val();
            var url = '<?php echo e(route('property.unit', ':id')); ?>';
            url = url.replace(':id', property_id);
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    property_id: property_id,
                },
                contentType: false,
                processData: false,
                type: 'GET',
                success: function(data) {
                    $('.unit').empty();
                    var unit =
                        `<select class="form-control hidesearch unit" id="unit" name="unit"></select>`;
                    $('.unit_div').html(unit);

                    $.each(data, function(key, value) {
                        $('.unit').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },

            });
        });
        $('#purchase_type').on('change', function() {
            let type = $(this).val();

            if (type === 'installment') {
                $('.purchase_installment').removeClass('d-none');
            } else {
                $('.purchase_installment').addClass('d-none');
                $('#installment_amount').val('');
            }
            calculateInstallmentAmount();
            calculateEndDate();
        });

        $(document).ready(function() {
            $('#purchase_type').trigger('change');
        });

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

            if (type === 'monthly') {
                endDate.setMonth(endDate.getMonth() + duration);
            } else if (type === 'yearly') {
                endDate.setFullYear(endDate.getFullYear() + duration);
            }

            // Format date as YYYY-MM-DD
            let year = endDate.getFullYear();
            let month = (endDate.getMonth() + 1).toString().padStart(2, '0');
            let day = endDate.getDate().toString().padStart(2, '0');

            $('#installment_end_date').val(`${year}-${month}-${day}`);
        }

        // Trigger calculation on changes
        $('#unit_price, #deposit, #installment_duration, #installment_fee_percent, #purchase_type,#installment_start_date,  #installment_type')
            .on('change keyup',
                function() {
                    calculateInstallmentAmount();
                    calculateEndDate();
                });
        // Also call once on page load in case of old values
        $(document).ready(function() {
            $('#purchase_type').trigger('change');
            calculateInstallmentAmount();
            calculateEndDate();
        });

        function calculateInstallmentAmount() {
            let unitPrice = parseFloat($('#unit_price').val());
            let deposit = parseFloat($('#deposit').val());
            let duration = parseInt($('#installment_duration').val());
            let feePercent = parseFloat($('#installment_fee_percent').val());
            let purchaseType = $('#purchase_type').val();

            if (purchaseType !== 'installment') {
                $('#installment_amount').val('');
                $('#price_after_deposit').val('');
                return;
            }

            if (isNaN(unitPrice) || isNaN(deposit) || isNaN(duration) || duration <= 0) {
                $('#installment_amount').val('');
                $('#price_after_deposit').val('');
                return;
            }

            let priceAfterDeposit = unitPrice - deposit;
            if (priceAfterDeposit < 0) priceAfterDeposit = 0;

            // Display price after deposit
            $('#price_after_deposit').val(priceAfterDeposit.toFixed(2));

            let installmentAmount = priceAfterDeposit / duration;

            // Add fee percent if provided
            if (!isNaN(feePercent) && feePercent > 0) {
                installmentAmount += installmentAmount * (feePercent / 100);
            }

            $('#installment_amount').val(installmentAmount.toFixed(2));
        }

        // Trigger calculation on relevant input changes
        $('#total_price, #deposit, #installment_duration, #purchase_type').on('change keyup', function() {
            calculateInstallmentAmount();
            calculateEndDate();
        });

        // Call on page load
        $(document).ready(function() {
            calculateInstallmentAmount();
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

                                <?php echo e(Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __('Enter First Name')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('last_name', __('Last Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('password', __('Password'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('phone_number', __('Phone Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('family_member', __('Total Family Member'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')])); ?>

                            </div>
                            <div class="form-group">
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

                                <?php echo e(Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')])); ?>

                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Enter Address')])); ?>

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

                                <?php echo e(Form::select('property', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('unit', __('Unit'), ['class' => 'form-label'])); ?>

                                <div class="unit_div">
                                    <select class="form-control hidesearch unit" id="unit" name="unit">
                                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6    ">
                                <?php echo e(Form::label('unit_price', __('Unit Price'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('unit_price', null, ['class' => 'form-control', 'id' => 'unit_price', 'min' => 0, 'step' => '0.01'])); ?>

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

                                <?php echo e(Form::select('installment_type', ['monthly' => __('Monthly'), 'yearly' => __('Yearly')], null, ['class' => 'form-control', 'id' => 'installment_type'])); ?>

                            </div>


                            
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_duration', __('Installment Duration'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_duration', null, ['class' => 'form-control', 'min' => 1])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('installment_start_date', null, ['class' => 'form-control'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('installment_end_date', null, ['class' => 'form-control', 'readonly'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('deposit', __('Deposit'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('deposit', null, ['class' => 'form-control', 'min' => 0, 'step' => '0.01'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_amount', __('Installment Amount'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_amount', null, ['class' => 'form-control', 'readonly'])); ?>

                            </div>

                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('price_after_deposit', __('Price After Deposit'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('price_after_deposit', null, ['class' => 'form-control', 'id' => 'price_after_deposit', 'readonly'])); ?>

                            </div>

                            <div class="form-group col-lg-6 col-md-6 purchase_installment d-none">
                                <?php echo e(Form::label('installment_fee_percent', __('Installment Fee %'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('installment_fee_percent', null, ['class' => 'form-control', 'id' => 'installment_fee_percent', 'step' => '0.01', 'min' => 0])); ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/tenant/create.blade.php ENDPATH**/ ?>