<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Edit')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>
    <script>
        $(function() {
            "use strict";

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

            $('#property-update').on('click', function(e) {
                e.preventDefault();
                $('#property-update').attr('disabled', true);
                var fd = new FormData($('#property_form')[0]);
                var files = myDropzone.getAcceptedFiles();
                $.each(files, function(key, file) {
                    fd.append('property_images[]', file, file.name);
                });
                fd.append('_method', 'PUT');

                $.ajax({
                    url: "<?php echo e(route('property.update', $property->id)); ?>",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: fd,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        $('#property-update').attr('disabled', false);
                        if (data.status == "success") {
                            toastrs('Success', data.msg, 'success');
                            var url = '<?php echo e(route('property.show', ':id')); ?>';
                            url = url.replace(':id', data.id);
                            setTimeout(() => {
                                window.location.href = url;
                            }, 1000);
                        } else {
                            toastrs('Error', data.msg, 'error');
                        }
                    },
                    error: function(data) {
                        $('#property-update').attr('disabled', false);
                        let errorMessage = 'An unexpected error occurred.';
                        if (data.responseJSON && data.responseJSON.msg) {
                            errorMessage = data.responseJSON.msg;
                        }
                        toastrs('Error', errorMessage, 'error');
                    },
                });
            });

            // JavaScript to handle image deletion without nested forms.
            $('.delete-property-image').on('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this image?')) {
                    var url = $(this).data('url');
                    $('#delete-image-form').attr('action', url).submit();
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a></li>
        <li class="breadcrumb-item active"><a href="#"><?php echo e(__('Edit')); ?></a></li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo e(Form::model($property, ['route' => ['property.update', $property->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'property_form'])); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        <div class="form-group ">
                            <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::select('type', $types, null, ['class' => 'form-control hidesearch'])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Name')])); ?>

                        </div>
                        <div class="form-group ">
                            <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'rows' => 8, 'placeholder' => __('Enter Property Description')])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('thumbnail', __('Change Thumbnail Image'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::file('thumbnail', ['class' => 'form-control'])); ?>

                        </div>
                        
                        <?php if($property->thumbnail): ?>
                            <div class="mt-2">
                                <label><?php echo e(__('Current Thumbnail')); ?></label>
                                <div class="text-center">
                                    <img src="<?php echo e(asset(Storage::url('upload/thumbnail/' . $property->thumbnail->image))); ?>"
                                        alt="Thumbnail" width="150" class="img-thumbnail">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        <div class="form-group">
                            <?php echo e(Form::label('country', __('Country'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Country')])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter Property State')])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter Property City')])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Zip Code')])); ?>

                        </div>
                        <div class="form-group ">
                            <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Property Address')])); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"><?php echo e(Form::label('demo-upload', __('Property Images'), ['class' => 'form-label'])); ?>

                </div>
                <div class="card-body">
                    <h5><?php echo e(__('Existing Images')); ?></h5>
                    <div class="row mb-4">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-3 text-center mb-3">
                                <img src="<?php echo e(asset(Storage::url('upload/property/' . $image->image))); ?>" alt="Image"
                                    class="img-fluid img-thumbnail mb-2" style="height: 150px; object-fit: cover;">
                                <button class="btn btn-danger btn-sm delete-property-image"
                                    data-url="<?php echo e(route('property.image.destroy', $image->id)); ?>">
                                    Delete
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p>No additional images uploaded.</p>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h5 class="mt-4"><?php echo e(__('Upload New Images')); ?></h5>
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
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                            <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="group-button text-end">
                <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'property-update'])); ?>

            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>


    
    <form id="delete-image-form" action="" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/property/edit.blade.php ENDPATH**/ ?>