<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    cdxuser-profile
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Tenant')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Details')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="row">
                <div class="col-xl-12 col-md-6">
                    <div class="card user-card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="user-imgwrap">
                                <img class="img-fluid rounded-50"
                                     src="<?php echo e((!empty($tenant->user) && !empty($tenant->user->profile))? asset(Storage::url("upload/profile/".$tenant->user->profile)): asset(Storage::url("upload/profile/avatar.png"))); ?>"
                                     alt="">
                            </div>
                            <div class="user-detailwrap">
                                <h3><?php echo e(ucfirst(!empty($tenant->user)?$tenant->user->first_name:'').' '.ucfirst(!empty($tenant->user)?$tenant->user->last_name:'')); ?></h3>
                                <h6><?php echo e(!empty($tenant->user)?$tenant->user->email:'-'); ?></h6>
                                <h6><?php echo e(!empty($tenant->user)?$tenant->user->phone_number:'-'); ?> </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="row">
                <div class="col-12">
                    <div class="card support-inboxtbl">
                        <div class="card-header">
                            <h4><?php echo e(__('Additional Information')); ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Total Family Member')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->family_member); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Country')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->country); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('State')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->state); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('City')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->city); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Zip Code')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->zip_code); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Property')); ?></h6>
                                            <p class="text-light"><?php echo e(!empty($tenant->properties)?$tenant->properties->name:'-'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Unit')); ?></h6>
                                            <p class="text-light"><?php echo e(!empty($tenant->units)?$tenant->units->name:'-'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Lease Start Date')); ?></h6>
                                            <p class="text-light"><?php echo e(dateFormat($tenant->lease_start_date)); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Lease End Date')); ?></h6>
                                            <p class="text-light"><?php echo e(dateFormat($tenant->lease_end_date)); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($tenant->documents)): ?>
                                    <div class="col-md-3 col-lg-3 mb-20">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6><?php echo e(__('Documents')); ?></h6>
                                                <?php $__currentLoopData = $tenant->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(asset(Storage::url('upload/tenant')).'/'.$doc->document); ?>"
                                                       class="text-light" target="_blank"><i
                                                                data-feather="download"></i></a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-6 col-lg-6 mb-20">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6><?php echo e(__('Address')); ?></h6>
                                            <p class="text-light"><?php echo e($tenant->address); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/tenant/show.blade.php ENDPATH**/ ?>