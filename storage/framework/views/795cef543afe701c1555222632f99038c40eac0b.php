<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php
    $admin_logo=getSettingsValByName('company_logo');
    $settings=settings();
?>
<?php $__env->startPush('script-page'); ?>
    <script>
        // This script is for the old print function, which can be kept as a secondary option if needed,
        // but the main button will now use the PDF controller.
        $(document).on('click', '.print-invoice-btn', function () {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('invoice.index')); ?>"><?php echo e(__('Invoice')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Details')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row mb-10">
        <div class="invoice-action ">
            
            <a class="btn btn-primary float-end" href="<?php echo e(route('pdf.download', ['type' => 'invoice', 'id' => $invoice->id])); ?>" target="_blank">
                <i data-feather="download" class="me-1"></i> <?php echo e(__('Export as PDF')); ?>

            </a>

            <?php if($invoice->status!='paid'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice payment')): ?>
                    <?php if(\Auth::user()->type=='tenant'): ?>
                        <a class="btn btn-secondary float-end me-2 collapsed" data-bs-toggle="collapse"
                           href="#paymentModal" role="button" aria-expanded="false"
                           aria-controls="collapse1"><?php echo e(__('Payment')); ?></a>
                    <?php else: ?>
                        <a class="btn btn-secondary float-end me-2 customModal" href="#" data-size="md"
                           data-url="<?php echo e(route('invoice.payment.create',$invoice->id)); ?>"
                           data-title="<?php echo e(__('Add Payment')); ?>"> <?php echo e(__('Add Payment')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    

    <div id="invoice-print">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body cdx-invoice">
                        <div id="cdx-invoice">
                            <div class="head-invoice">
                                <div class="codex-brand">
                                    <a class="codexbrand-logo" href="Javascript:void(0);">
                                        <img class="img-fluid invoice-logo" src=" <?php echo e(asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')); ?>" alt="invoice-logo">
                                    </a>
                                </div>
                                <ul class="contact-list">
                                    <li><div class="icon-wrap"><i class="fa fa-user"></i></div><?php echo e($settings['company_name'] ?? ''); ?></li>
                                    <li><div class="icon-wrap"><i class="fa fa-phone"></i></div><?php echo e($settings['company_phone'] ?? ''); ?></li>
                                    <li><div class="icon-wrap"><i class="fa fa-envelope"></i></div><?php echo e($settings['company_email'] ?? ''); ?></li>
                                </ul>
                            </div>
                            <div class="invoice-user">
                                <div class="left-user">
                                    <h5><?php echo e(__('Inovice to')); ?>:</h5>
                                    <ul class="detail-list">
                                        <li><div class="icon-wrap"><i class="fa fa-user"></i></div><?php echo e($invoice->tenant?->user?->first_name); ?> <?php echo e($invoice->tenant?->user?->last_name); ?></li>
                                        <li><div class="icon-wrap"><i class="fa fa-phone"></i></div><?php echo e($invoice->tenant?->user?->phone_number ?? '-'); ?></li>
                                        <li><div class="icon-wrap"><i class="fa fa-map-marker"></i></div><?php echo e($invoice->tenant?->address ?? ''); ?></li>
                                    </ul>
                                </div>
                                <div class="right-user">
                                    <ul class="detail-list">
                                        <li><?php echo e(__('Status')); ?>:
                                            <?php if($invoice->status=='paid'): ?>
                                                <span class="badge badge-success"><?php echo e(ucfirst($invoice->status)); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-primary"><?php echo e(ucfirst($invoice->status)); ?></span>
                                            <?php endif; ?>
                                        </li>
                                        <li><?php echo e(__('Invoice No')); ?>: <span><?php echo e($invoice->invoice_id); ?> </span></li>
                                        <li><?php echo e(__('Invoice Month')); ?>:<span> <?php echo e(\Carbon\Carbon::parse($invoice->invoice_month)->format('F Y')); ?> </span></li>
                                        <li><?php echo e(__('End Date')); ?>: <span><?php echo e(\Carbon\Carbon::parse($invoice->end_date)->format('M j, Y')); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="body-invoice">
                                <div class="table-responsive1">
                                    <table class="table ml-1">
                                        <thead><tr><th><?php echo e(__('Type')); ?></th><th><?php echo e(__('Description')); ?></th><th><?php echo e(__('Amount')); ?></th></tr></thead>
                                        <tbody>
                                            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($item->invoice_type); ?></td>
                                                    <td><?php echo e($item->description); ?></td>
                                                    <td>$<?php echo e(number_format($item->amount, 2)); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="footer-invoice">
                                <table class="table">
                                    <tr>
                                        <td><?php echo e(__('Total')); ?></td>
                                        <td>$<?php echo e(number_format($invoice->items->sum('amount'), 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(__('Due Amount')); ?></td>
                                        <td>$<?php echo e(number_format($invoice->items->sum('amount'), 2)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5><?php echo e(__('Payment History')); ?></h5></div>
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance1">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Transaction Id')); ?></th>
                                <th><?php echo e(__('Payment Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <th><?php echo e(__('Type')); ?></th>
                                <th><?php echo e(__('Notes')); ?></th>
                                <th><?php echo e(__('Receipt')); ?></th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete invoice payment')): ?>
                                    <th class="text-right"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr role="row">
                                    <td><?php echo e($payment->transaction_id); ?> </td>
                                    <td><?php echo e(dateFormat($payment->payment_date)); ?> </td>
                                    <td><?php echo e(priceFormat($payment->amount)); ?> </td>
                                    <td><?php echo e(__($payment->payment_type)); ?> </td>
                                    <td><?php echo e($payment->notes); ?> </td>
                                    <td>
                                        <?php if(!empty($payment->receipt)): ?>
                                            <?php if($payment->payment_type=='Stripe'): ?>
                                                <a href="<?php echo e($payment->receipt); ?>" target="_blank"><i data-feather="eye"></i></a>
                                            <?php else: ?>
                                                <a href="<?php echo e(asset(Storage::url('upload/receipt')).'/'.$payment->receipt); ?>" download="download"><i data-feather="download"></i></a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete invoice payment')): ?>
                                        <td class="text-right">
                                            <div class="cart-action">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.destroy', $invoice->id,$payment->id]]); ?>

                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                        data-feather="trash-2"></i></a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/show.blade.php ENDPATH**/ ?>