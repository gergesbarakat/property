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


 
<?php $__env->startSection('styles'); ?>
    
    <style>
        .table-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e9ecef;
        }

        .modern-table {
            border-collapse: collapse;
            width: 100%;
        }

        .modern-table thead th {
            font-weight: 600;
            background-color: #fff;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .modern-table td,
        .modern-table th {
            vertical-align: middle !important;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .modern-table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .modern-table .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        /* ✅ CSS for ghost buttons has been removed to allow for colors. */
        .modern-table .action-buttons .btn {
            width: 32px;
            height: 32px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 3px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="table-header  p-20 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Buyers</h4>
         </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table modern-table" id="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Buyer</th>
                            <th>Property</th>
                            <th>Unit</th>
                            <th>Contract Start</th>
                            <th>Contract End</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if(optional($tenant->user)->profile): ?>
                                            <img class="rounded-circle avatar"
                                                src="<?php echo e(Storage::url($tenant->user->profile)); ?>" alt="Profile">
                                        <?php else: ?>
                                            <div
                                                class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary">
                                                <span
                                                    style="font-size: 1rem; font-weight: 600;"><?php echo e(substr(optional($tenant->user)->first_name, 0, 1)); ?><?php echo e(substr(optional($tenant->user)->last_name, 0, 1)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="ms-3">
                                            <strong class="d-block"><?php echo e(optional($tenant->user)->first_name); ?>

                                                <?php echo e(optional($tenant->user)->last_name); ?></strong>
                                            <small class="text-muted"><?php echo e(optional($tenant->user)->email ?? '-'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e(optional($tenant->linked_property)->name ?? '-'); ?></td>
                                <td><?php echo e(optional($tenant->propertyUnit)->name ?? '-'); ?></td>
                                <td>
                                    <?php if($tenant->installments->isNotEmpty()): ?>
                                        <?php echo e(\Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('M j, Y')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($tenant->installments->isNotEmpty()): ?>
                                        <?php echo e(\Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('M j, Y')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center action-buttons">
                                    
                                    <a href="<?php echo e(route('tenant.show', $tenant->id)); ?>"
                                        class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="View"><i
                                            data-feather="eye"></i></a>
                                    <a href="<?php echo e(route('tenant.edit', $tenant->id)); ?>"
                                        class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Edit"><i
                                            data-feather="edit"></i></a>
                                    <form action="<?php echo e(route('tenant.destroy', $tenant->id)); ?>" method="POST"
                                        class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger text-white"
                                            data-bs-toggle="tooltip" title="Delete"
                                            onclick="return confirm('Are you sure?')"><i data-feather="trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
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


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH O:\JOWEB\property\resources\views/tenant/index.blade.php ENDPATH**/ ?>