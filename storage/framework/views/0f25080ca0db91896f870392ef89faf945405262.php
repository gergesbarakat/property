<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
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
            <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Details')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create unit')): ?>
        <div class="row">
            <div class="col-sm-12 text-end">
                <a href="#" class="btn btn-primary btn-sm customModal" data-title="<?php echo e(__('Add Unit')); ?>"
                    data-url="<?php echo e(route('unit.create', $property->id)); ?>" data-size="lg"> <i
                        class="ti-plus mr-5"></i><?php echo e(__('Add Unit')); ?></a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5 cdx-xl-45">
            <div class="product-card">
                <div class="product-for">
                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($image) && !empty($image->image)): ?>
                            <?php  $img= $image->image; ?>
                        <?php else: ?>
                            <?php  $img= 'default.jpg'; ?>
                        <?php endif; ?>
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="<?php echo e(asset(Storage::url('upload/property')) . '/' . $img); ?>"
                                    alt="">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="product-to">
                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($image) && !empty($image->image)): ?>
                            <?php  $img= $image->image; ?>
                        <?php else: ?>
                            <?php  $img= 'default.jpg'; ?>
                        <?php endif; ?>
                        <div>
                            <div class="product-imgwrap">
                                <img class="img-fluid" src="<?php echo e(asset(Storage::url('upload/property')) . '/' . $img); ?>"
                                    alt="">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7 cdx-xl-55 cdxpro-detail">
            <div class="product-card">
                <div class="detail-group">
                    <div class="media">
                        <div>
                            <h2><?php echo e($property->name); ?></h2>
                            <h6 class="text-light">
                                <div class="date-info">
                                    <span class="badge badge-primary" data-bs-toggle="tooltip"
                                        data-bs-original-title="<?php echo e(__('Type')); ?>"><?php echo e(\App\Models\Property::$Type[$property->type]); ?></span>
                                </div>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="detail-group">
                    <h6><?php echo e(__('Property Details')); ?></h6>
                    <p class="mb-10"><?php echo e($property->description); ?></p>

                </div>
                <div class="detail-group">
                    <h6><?php echo e(__('Property Address')); ?></h6>
                    <p class="mb-10"><?php echo e($property->address); ?></p>
                    <p class="mb-10"><?php echo e($property->city . ', ' . $property->state . ', ' . $property->country); ?></p>
                    <p class="mb-10"><?php echo e($property->zip_code); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-4 bg-white p-30 rounded">
        <div class="p-2 h4">Property Units</div>
        <table class="table table-bordered   align-middle" id='invoice-table'>
            <thead class="table-light">
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
                                            data-url="<?php echo e(route('unit.edit', [$unit->property_id, $unit->id])); ?>" href="#"
                                            data-size="lg" data-title="<?php echo e(__('Edit Unit')); ?>" data-bs-toggle="tooltip"
                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/property/show.blade.php ENDPATH**/ ?>