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
                   

                    <table id="invoice-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Invoice')); ?></th>
                                <th><?php echo e(__('Property')); ?></th>
                                <th><?php echo e(__('Unit')); ?></th>
                                <th><?php echo e(__('Invoice Month')); ?></th>
                                <th><?php echo e(__('End Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
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
                                    <td><?php echo e(priceFormat(number_format($invoice->items->sum('amount'), 2))); ?></td>
                                    <td>
                                        <?php if($invoice->status == 'paid'): ?>
                                            <span class="badge badge-success"><?php echo e(ucfirst($invoice->status)); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-primary"><?php echo e(ucfirst($invoice->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                        <td class="text-right">
                                            <div class="cart-action">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show invoice')): ?>
                                                    <a class="text-warning" href="<?php echo e(route('invoice.show', $invoice->id)); ?>"
                                                        data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('View')); ?>">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                
                                                
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <script>
                        function deleteInvoice(id) {
                            if (confirm("Are you sure you want to delete this invoice?")) {
                                $.ajax({
                                    url: "<?php echo e(url('invoice')); ?>/" + id,
                                    type: 'POST',
                                    data: {
                                        '_method': 'DELETE',
                                        '_token': '<?php echo e(csrf_token()); ?>'
                                    },
                                    success: function(result) {
                                        location.reload();
                                    },
                                    error: function(xhr) {
                                        alert('Delete failed!');
                                    }
                                });
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/index.blade.php ENDPATH**/ ?>