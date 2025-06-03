<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Units')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Units')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance" id="invoice-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Property')); ?></th>

                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Bedroom')); ?></th>
                                <th><?php echo e(__('Kitchen')); ?></th>
                                <th><?php echo e(__('Bath')); ?></th>
                                
                                
                                <th><?php echo e(__('Created ')); ?></th>
                                <th><?php echo e(__('Updated')); ?></th>

                                <?php if(Gate::check('edit unit') || Gate::check('delete unit')): ?>
                                    <th class="text-center"><?php echo e(__('Actions')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($unit->properties->name); ?></td>

                                    <td><?php echo e($unit->name); ?></td>
                                    <td><?php echo e($unit->bedroom); ?></td>
                                    <td><?php echo e($unit->kitchen); ?></td>
                                    <td><?php echo e($unit->baths); ?></td>
                                    
                                    <td><?php echo e($unit->created_at); ?></td>
                                    <td><?php echo e($unit->updated_at); ?></td>

                                    <?php if(Gate::check('edit unit') || Gate::check('delete unit')): ?>
                                        <td class="text-right">
                                            <div class="cart-action">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['unit.destroy', [$unit->property_id, $unit->id]]]); ?>


                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit unit')): ?>
                                                    <a class="text-success customModal"
                                                        data-url="<?php echo e(route('unit.edit', [$unit->property_id, $unit->id])); ?>"
                                                        href="#" data-size="lg" data-title="<?php echo e(__('Edit Unit')); ?>"
                                                        data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                        <i data-feather="edit"></i></a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete unit')): ?>
                                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                            data-feather="trash-2"></i></a>
                                                <?php endif; ?>
                                                <?php echo Form::close(); ?>

                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/unit/index.blade.php ENDPATH**/ ?>