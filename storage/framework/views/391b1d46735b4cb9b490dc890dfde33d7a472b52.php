<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Buyer Edit')); ?>

<?php $__env->stopSection(); ?>
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
                addRemoveLinks: true, // This enables the remove link functionality
                dictRemoveFile: '×', // Sets the text for the remove link
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
                    url: "<?php echo e(route('tenant.update', $tenant->id)); ?>",
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
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Buyer')); ?></a></li>
        <li class="breadcrumb-item active"><a href="#"><?php echo e(__('Edit')); ?></a></li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo e(Form::model($tenant, ['route' => ['tenant.update', $tenant->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form'])); ?>

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

                                <?php echo e(Form::text('first_name', $user->first_name, ['class' => 'form-control', 'placeholder' => __('Enter First Name')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('last_name', __('Last Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('last_name', $user->last_name, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter Email')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('phone_number', __('Phone Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('phone_number', $user->phone_number, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('national_id', __('National ID'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('national_id', null, ['class' => 'form-control', 'placeholder' => __('Enter National ID')])); ?>

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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('Property Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <h6><?php echo e(__('Property')); ?></h6>
                                <p class="form-control-static"><?php echo e(optional($tenant->linked_property)->name ?? 'N/A'); ?></p>
                            </div>
                            <div class="form-group col-md-6">
                                <h6><?php echo e(__('Unit')); ?></h6>
                                <p class="form-control-static"><?php echo e(optional($tenant->propertyUnit)->name ?? 'N/A'); ?></p>
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
                    <h5><?php echo e(__('Existing Documents')); ?></h5>
                    <?php if($tenant->contracts->isNotEmpty()): ?>
                        <ul class="list-group mb-4">
                            <?php $__currentLoopData = $tenant->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo e(Storage::url($contract->contract_file)); ?>"
                                        target="_blank"><?php echo e(basename($contract->contract_file)); ?></a>
                                    <form action="<?php echo e(route('tenant.contract.destroy', $contract->id)); ?>" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this document?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No documents have been uploaded yet.</p>
                    <?php endif; ?>
                    <hr>
                    <h5 class="mt-4"><?php echo e(__('Upload New Documents')); ?></h5>
                    <div class="dropzone needsclick" id='demo-upload' action="#">
                        <div class="dz-message needsclick">
                            <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                            <h3><?php echo e(__('Drop new files here or click to upload.')); ?></h3>
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
                            <a href="#!" class="dz-remove" data-dz-remove></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="group-button text-end">
                <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'tenant-submit'])); ?>

            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/tenant/edit.blade.php ENDPATH**/ ?>