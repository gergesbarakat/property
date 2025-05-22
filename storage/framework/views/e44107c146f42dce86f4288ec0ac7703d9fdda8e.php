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
    <div class="row">
        <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo e(__('Photo')); ?></th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Email')); ?></th>
                            <th><?php echo e(__('Phone')); ?></th>
                            <th><?php echo e(__('Address')); ?></th>
                            <th><?php echo e(__('Property')); ?></th>
                            <th><?php echo e(__('Unit')); ?></th>
                            <th><?php echo e(__('Family Members')); ?></th>
                            <th><?php echo e(__('Lease Start')); ?></th>
                            <th><?php echo e(__('Lease End')); ?></th>
                            <?php if(Gate::check('edit tenant') || Gate::check('delete tenant') || Gate::check('show tenant')): ?>
                                <th><?php echo e(__('Actions')); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="user-imgwrapper" style="width: 40px; height: 40px;">
                                        <img class="img-fluid rounded-circle"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            src="<?php echo e(!empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png'))); ?>"
                                            alt="<?php echo e(!empty($tenant->user) ? $tenant->user->first_name : 'Tenant'); ?>">
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('tenant.show', $tenant->id)); ?>" class="text-decoration-none">
                                        <strong><?php echo e(ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '')); ?></strong>
                                    </a>
                                </td>
                                <td><?php echo e(!empty($tenant->user) ? $tenant->user->email : '-'); ?></td>
                                <td><?php echo e(!empty($tenant->user) ? $tenant->user->phone_number : '-'); ?></td>
                                <td><?php echo e($tenant->address ?: '-'); ?></td>
                                <td><?php echo e(!empty($tenant->properties) ? $tenant->properties->name : '-'); ?></td>
                                <td><?php echo e(!empty($tenant->units) ? $tenant->units->name : '-'); ?></td>
                                <td><?php echo e($tenant->family_member); ?></td>
                                <td><?php echo e(dateFormat($tenant->lease_start_date)); ?></td>
                                <td><?php echo e(dateFormat($tenant->lease_end_date)); ?></td>
                                <?php if(Gate::check('edit tenant') || Gate::check('delete tenant') || Gate::check('show tenant')): ?>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show tenant')): ?>
                                            <a class=" " href="<?php echo e(route('tenant.show', $tenant->id)); ?>">
                                                <i data-feather="eye" class="me-2"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit tenant')): ?>
                                            <a class=" " href="<?php echo e(route('tenant.edit', $tenant->id)); ?>">
                                                <i data-feather="edit" class="me-2"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete tenant')): ?>
                                            <?php echo Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['tenant.destroy', $tenant->id],
                                                'id' => 'tenant-' . $tenant->id,
                                                'class' => 'd-inline',
                                            ]); ?>

                                            <a href="#" class="  text-danger confirm_dialog">
                                                <i data-feather="trash" class="me-2"></i>
                                            </a>
                                            <?php echo Form::close(); ?>

                                        <?php endif; ?>
                                        </ul>
            </div>
            </td>
        <?php endif; ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/tenant/index.blade.php ENDPATH**/ ?>