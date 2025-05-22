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
                    <div class="d-flex">
                        <button id="generate-pdf" class="btn btn-outline-primary">
                            PDF
                        </button>

                        <!-- Export to Excel Button -->
                        <button onclick="exportToExcel()" class="btn btn-outline-success">
                            Export to Excel
                        </button>
                    </div>
                    <table class="bg-white p-3 w-full position-relative" id="invoice-table">
                        <thead>
                            <tr>
                                <td><span><?php echo e(__('Property')); ?> </span></td>

                                <td><span><?php echo e(__('Unit')); ?> </span></td>

                                <td><span><?php echo e(__('Bedroom')); ?> </span></td>
                                <td><span><?php echo e(__('Kitchen')); ?> </span></td>
                                <td><span><?php echo e(__('Bath')); ?> </span></td>
                                <td><span><?php echo e(__('Unit Price')); ?> </span></td>
                                <td><span><?php echo e(__('Status')); ?> </span></td>
                                <td>
                                    <span><?php echo e(__('Date Created')); ?> </span>
                                </td>
                                <td>
                                    <span><?php echo e(__('Date Updated')); ?> </span>
                                </td>
                                <td> <span><?php echo e(__('Actions')); ?>

                                    </span></td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($unit->property->name); ?></td>

                                    <td><?php echo e($unit->name); ?></td>


                                    <td><?php echo e($unit->bedroom); ?> </td>
                                    <td><?php echo e($unit->kitchen); ?></td>
                                    <td><?php echo e($unit->baths); ?></td>
                                    <td><?php echo e(priceFormat($unit->rent)); ?></td>

                                    <td><?php echo e($unit->status); ?></td>
                                    <td>
                                        <?php echo e(dateFormat($unit->created_at)); ?>

                                    </td>
                                    <td>
                                        <?php echo e(dateFormat($unit->updated_at)); ?>

                                    </td>
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