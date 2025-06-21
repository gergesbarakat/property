<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Invoice')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable   datatbl-advance">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Invoice')); ?></th>
                                <th><?php echo e(__('Property')); ?></th>
                                <th><?php echo e(__('Unit')); ?></th>
                                <th><?php echo e(__('Invoice Month')); ?></th>
                                <th><?php echo e(__('End Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <th><?php echo e(__('type')); ?></th>

                                <th><?php echo e(__('Status')); ?></th>
                                <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                    <th class="text-right"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr role="row">
                                    <td><?php echo e($invoice->invoice_id); ?></td>
                                    <td><?php echo e(optional($invoice->property)->name ?? '-'); ?></td>
                                    <td><?php echo e(optional($invoice->unit)->name ?? '-'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($invoice->invoice_month)->format('F Y')); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($invoice->end_date)->format('M j, Y')); ?></td>

                                    
                                    <td>$<?php echo e(number_format($invoice->payments->sum('amount'), 2)); ?></td>
                                    <td><?php echo e($invoice->payments->first()->notes); ?></td>
                                    <td>
                                        <?php if($invoice->status == 'paid'): ?>
                                            <span class="badge badge-success"><?php echo e(ucfirst($invoice->status)); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-primary"><?php echo e(ucfirst($invoice->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                             <a class="dropdown-item" href="<?php echo e(route('invoice.show', $invoice->id)); ?>">
                                                <i class="ti-eye"></i> <?php echo e(__('View')); ?>

                                            </a>

 

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/index.blade.php ENDPATH**/ ?>