<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Tenant')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create tenant')): ?>
        <a class="btn btn-primary btn-sm ml-20" href="<?php echo e(route('tenant.create')); ?>" data-size="md"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Tenant')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="w-full p-10 bg-white">
                <div class="  p-10">
                    <table class="table table-bordered table-striped" id="invoice-table">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Family Member</th>
                                <th>Property</th>
                                <th>Unit</th>
                                <th>Lease Start Date</th>
                                <th>Lease End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <img class="rounded-circle" width="50" height="50"
                                         src="<?php echo e((!empty($tenant->user) && !empty($tenant->user->profile))
                                             ? asset(Storage::url("upload/profile/" . $tenant->user->profile))
                                             : asset(Storage::url("upload/profile/avatar.png"))); ?>"
                                         alt="Profile">
                                </td>
                                <td><?php echo e(ucfirst(optional($tenant->user)->first_name)); ?> <?php echo e(ucfirst(optional($tenant->user)->last_name)); ?></td>
                                <td><?php echo e(optional($tenant->user)->email ?? '-'); ?></td>
                                <td><?php echo e(optional($tenant->user)->phone_number ?? '-'); ?></td>
                                <td><?php echo e($tenant->address); ?></td>
                                <td><?php echo e($tenant->family_member); ?></td>
                                <td><?php echo e(optional($tenant->properties)->name ?? '-'); ?></td>
                                <td><?php echo e(optional($tenant->units)->name ?? '-'); ?></td>
                                <td><?php echo e(dateFormat($tenant->lease_start_date)); ?></td>
                                <td><?php echo e(dateFormat($tenant->lease_end_date)); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show tenant')): ?>
                                            <a href="<?php echo e(route('tenant.show', $tenant->id)); ?>" class="btn btn-sm btn-primary" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit tenant')): ?>
                                            <a href="<?php echo e(route('tenant.edit', $tenant->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete tenant')): ?>
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['tenant.destroy', $tenant->id], 'style' => 'display:inline']); ?>

                                            <button type="submit" class="btn btn-sm btn-danger confirm_dialog" title="Delete">
                                                <i data-feather="trash"></i>
                                            </button>
                                            <?php echo Form::close(); ?>

                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/tenant/index.blade.php ENDPATH**/ ?>