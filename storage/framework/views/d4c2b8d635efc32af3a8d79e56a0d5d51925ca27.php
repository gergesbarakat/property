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
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Tenant')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Details')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .user-card .user-imgwrap {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }
        .user-card .user-imgwrap img {
            width: 100px;
            height: 100px;
            border: 5px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .user-card .card-body {
            margin-top: 60px;
        }
        .multi-header-table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .5px;
            padding: 0.75rem;
        }
        .multi-header-table .info-header th {
            background-color: #e9ecef;
            font-size: 1rem;
            text-transform: none;
            color: #495057;
        }
        .multi-header-table .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 20%;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="card user-card">
                <div class="card-header" style="min-height: 50px;"></div>
                <div class="card-body text-center">
                    <div class="user-imgwrap"><img class="img-fluid rounded-circle"
                            src="<?php echo e(optional($tenant->user)->profile ? Storage::url($tenant->user->profile) : asset('path/to/default/avatar.png')); ?>"
                            alt="Profile Image"></div>
                    <div class="user-detailwrap">
                        <h3><?php echo e(optional($tenant->user)->first_name); ?> <?php echo e(optional($tenant->user)->last_name); ?></h3>
                        <h6><?php echo e(optional($tenant->user)->email ?? '-'); ?></h6>
                        <h6><?php echo e(optional($tenant->user)->phone_number ?? '-'); ?></h6>
                        <?php if($tenant->contracts->isNotEmpty()): ?>
                            <a href="<?php echo e(route('tenants.contracts.download', $tenant->id)); ?>"
                                class="btn btn-primary btn-sm mt-3">
                                <i data-feather="download-cloud" class="me-1" style="width:16px; height:16px;"></i>
                                <?php echo e(__('Download Documents')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        
                        <table class="table multi-header-table">
                            
                            <thead class="info-header">
                                <tr>
                                    <th colspan="5"><h4><?php echo e(__('Additional Information')); ?></h4></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="info-label"><?php echo e(__('Property')); ?></td>
                                    <td><?php echo e(optional($tenant->linked_property)->name ?? '-'); ?></td>
                                    <td class="info-label"><?php echo e(__('Unit')); ?></td>
                                    <td colspan="2"><?php echo e(optional($tenant->propertyUnit)->name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label"><?php echo e(__('Address')); ?></td>
                                    <td colspan="4"><?php echo e($tenant->address ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label"><?php echo e(__('City / State')); ?></td>
                                    <td><?php echo e($tenant->city ?? ''); ?><?php echo e($tenant->city && $tenant->state ? ', ' : ''); ?><?php echo e($tenant->state ?? ''); ?></td>
                                    <td class="info-label"><?php echo e(__('Country')); ?></td>
                                    <td colspan="2"><?php echo e($tenant->country ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label"><?php echo e(__('Total Family')); ?></td>
                                    <td><?php echo e($tenant->family_member ?? '-'); ?></td>
                                    <td class="info-label"><?php echo e(__('Zip Code')); ?></td>
                                    <td colspan="2"><?php echo e($tenant->zip_code ?? '-'); ?></td>
                                </tr>
                            </tbody>

                            
                            <?php if($tenant->installments->isNotEmpty()): ?>
                                <thead class="info-header" style="border-top: 2px solid #dee2e6;">
                                    <tr>
                                        <th colspan="5"><h4><?php echo e(__('Installment Plan')); ?></h4></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $tenant->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($installment->installment_number); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($installment->due_date)->format('F j, Y')); ?></td>
                                            <td>$<?php echo e(number_format($installment->amount, 2)); ?></td>
                                            <td class="text-center">
                                                <?php if($installment->status == 'paid'): ?>
                                                    <span class="badge bg-success text-white">Paid</span>
                                                <?php elseif($installment->status == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger text-white">Overdue</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($installment->status != 'paid'): ?>
                                                    <form
                                                        action="<?php echo e(route('installments.updateStatus', $installment->id)); ?>"
                                                        method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success">Mark as Paid</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span>-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">No installment plan found for this buyer.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            <?php else: ?>
                                <thead class="info-header" style="border-top: 2px solid #dee2e6;">
                                    <tr>
                                        <th colspan="5"><h4><?php echo e(__('Payment Information')); ?></h4></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            This was a full payment purchase. No installment plan is available.
                                        </td>
                                    </tr>
                                </tbody>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/tenant/show.blade.php ENDPATH**/ ?>