<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Buyer')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Buyer')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create tenant')): ?>
        <a class="btn btn-primary btn-sm ml-20" href="<?php echo e(route('tenant.create')); ?>" data-size="md"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Buyer')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="table-header  p-20 d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?php echo e(__('All Buyers')); ?></h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table modern-table" id="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;"><?php echo e(__('Buyer')); ?></th>
                            <th style="width: 15%;"><?php echo e(__('Property')); ?></th>
                            <th style="width: 15%;"><?php echo e(__('Unit')); ?></th>
                            <th style="width: 12%;"><?php echo e(__('Start date')); ?></th>
                            <th style="width: 12%;"><?php echo e(__('End date')); ?></th>
                            <th style="width: 11%;"><?php echo e(__('Purchase Type')); ?></th>
                            
                            <th style="width: 10%;" class="text-end"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong class="d-block"><?php echo e(optional($tenant->user)->first_name); ?>

                                                <?php echo e(optional($tenant->user)->last_name); ?></strong>
                                            <small class="text-muted"><?php echo e(optional($tenant->user)->email ?? '-'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e(optional($tenant->linked_property)->name ?? '-'); ?></td>
                                <td><?php echo e(optional($tenant->propertyUnit)->name ?? '-'); ?></td>
                                <td class="whitespace-nowrap">
                                    <?php if($tenant->installments->isNotEmpty()): ?>
                                        <?php echo e(\Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('Y-m-d')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="whitespace-nowrap">
                                    <?php if($tenant->installments->isNotEmpty()): ?>
                                        <?php echo e(\Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('Y-m-d')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(ucfirst($tenant->purchase_type)); ?></td>
                                
                                <td class="text-end action-buttons">
                                    <a href="<?php echo e(route('tenant.show', $tenant->id)); ?>"
                                        class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="View"><i
                                            data-feather="eye"></i></a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit tenant')): ?>
                                        <a href="<?php echo e(route('tenant.edit', $tenant->id)); ?>"
                                            class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Edit"><i
                                                data-feather="edit"></i></a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h5 class="text-muted">No buyers found.</h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/tenant/index.blade.php ENDPATH**/ ?>