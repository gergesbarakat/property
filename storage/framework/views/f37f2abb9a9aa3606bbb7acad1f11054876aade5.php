<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Property')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('card-action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create property')): ?>
        <a class="btn btn-primary btn-sm ml-20" href="<?php echo e(route('property.create')); ?>" data-size="md"> <i
                class="ti-plus mr-5"></i><?php echo e(__('Create Property')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!empty($property->thumbnail) && !empty($property->thumbnail->image)): ?>
                <?php  $thumbnail= $property->thumbnail->image; ?>
            <?php else: ?>
                <?php  $thumbnail= 'default.jpg'; ?>
            <?php endif; ?>
            <div class="col-xl-3 col-sm-6 cdx-xl-50">
                <div class="card blog-wrapper">
                    <div class="imgwrapper">
                        <img class="img-fluid property-img"
                             src="<?php echo e(asset(Storage::url('upload/thumbnail')).'/'.$thumbnail); ?>" alt="<?php echo e($property->name); ?>">
                        <a class="hover-link" href="<?php echo e(route('property.show',$property->id)); ?>"><i
                                data-feather="link"></i></a></div>
                    <div class="detailwrapper">
                        <a href="<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show property')): ?> <?php echo e(route('property.show',$property->id)); ?> <?php endif; ?>">
                            <h4><?php echo e($property->name); ?></h4></a>
                        <ul class="blogsoc-list">
                            <li><a href="#"><i data-feather="layers"></i><?php echo e($property->totalUnit()); ?>  <?php echo e(__('Unit')); ?></a></li>
                            <li><a href="#"><i data-feather="layout"></i><?php echo e($property->totalRoom()); ?> <?php echo e(__('Rooms')); ?></a></li>
                        </ul>
                        <p class="text-justify"><?php echo e(substr($property->description, 0, 200)); ?><?php echo e(!empty($property->description)?'...':''); ?></p>
                        <div class="blog-footer">
                            <div class="date-info">
                                <span class="badge badge-primary" data-bs-toggle="tooltip"
                                      data-bs-original-title="<?php echo e(__('Type')); ?>"><?php echo e(\App\Models\Property::$Type[$property->type]); ?></span>
                            </div>
                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]); ?>

                            <div class="date-info">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit property')): ?>
                                    <a class="text-success" data-bs-toggle="tooltip"
                                       data-bs-original-title="<?php echo e(__('Edit')); ?>"
                                       href="<?php echo e(route('property.edit',$property->id)); ?>"> <i
                                            data-feather="edit"></i></a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete property')): ?>
                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                       data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                            data-feather="trash-2"></i></a>
                                <?php endif; ?>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/property/index.blade.php ENDPATH**/ ?>