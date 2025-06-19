<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php
    $admin_logo=getSettingsValByName('company_logo');
    $settings=settings();
?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '.print', function () {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        });

    </script>
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
        <?php if( $invoicePaymentSettings['STRIPE_PAYMENT'] == 'on' && !empty($invoicePaymentSettings['STRIPE_KEY']) && !empty($invoicePaymentSettings['STRIPE_SECRET'])): ?>
        var stripe_key = Stripe('<?php echo e($invoicePaymentSettings['STRIPE_KEY']); ?>');
        var stripe_elements = stripe_key.elements();
        var strip_css = {
            base: {
                fontSize: '14px',
                color: '#32325d',
            },
        };
        var stripe_card = stripe_elements.create('card', {style: strip_css});
        stripe_card.mount('#card-element');

        var stripe_form = document.getElementById('stripe-payment');
        stripe_form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe_key.createToken(stripe_card).then(function (result) {
                if (result.error) {
                    $("#stripe_card_errors").html(result.error.message);
                    $.NotificationApp.send("Error", result.error.message, "top-right", "rgba(0,0,0,0.2)", "error");
                } else {
                    var token = result.token;
                    var stripeForm = document.getElementById('stripe-payment');
                    var stripeHiddenData = document.createElement('input');
                    stripeHiddenData.setAttribute('type', 'hidden');
                    stripeHiddenData.setAttribute('name', 'stripeToken');
                    stripeHiddenData.setAttribute('value', token.id);
                    stripeForm.appendChild(stripeHiddenData);
                    stripeForm.submit();
                }
            });
        });
        <?php endif; ?>

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
            <a class="btn btn-info float-end print" href="javascript:void(0);"> <?php echo e(__('Print Invoice')); ?></a>
            <?php if($invoice->status!='paid'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice payment')): ?>
                    <?php if(\Auth::user()->type=='tenant'): ?>
                        <a class="btn btn-primary float-end me-2 collapsed" data-bs-toggle="collapse"
                           href="#paymentModal" role="button" aria-expanded="false"
                           aria-controls="collapse1"><?php echo e(__('Payment')); ?></a>
                    <?php else: ?>
                        <a class="btn btn-primary float-end me-2 customModal" href="#" data-size="md"
                           data-url="<?php echo e(route('invoice.payment.create',$invoice->id)); ?>"
                           data-title="<?php echo e(__('Add Payment')); ?>"> <?php echo e(__('Add Payment')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-25 collapse" id="paymentModal" style="">
        <div class="card card-body ">
            <div class="col-xxl-12 cdx-xxl-100">
                <div class="payment-method">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-0 mb-15">
                            <?php if($settings['bank_transfer_payment'] == 'on'): ?>
                                <li><a class="btn active" data-bs-toggle="tab"
                                       href="#bank_transfer"><?php echo e(__('Bank Transfer')); ?> </a></li>
                            <?php endif; ?>
                            <?php if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET'])): ?>
                                <li><a class="btn " data-bs-toggle="tab"
                                       href="#stripe_payment"><?php echo e(__('Stripe')); ?> </a></li>
                            <?php endif; ?>
                            <?php if($settings['paypal_payment'] == 'on' && !empty($settings['paypal_client_id']) && !empty($settings['paypal_secret_key'])): ?>
                                <li><a class="btn" data-bs-toggle="tab" href="#paypal_payment"><?php echo e(__('Paypal')); ?></a></li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content">
                            <?php if($settings['bank_transfer_payment'] == 'on'): ?>
                                <div class="tab-pane fade active show" id="bank_transfer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="<?php echo e(route('invoice.banktransfer.payment',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                                    method="post" class="require-validation" id="bank-payment"
                                                    enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Bank Name')); ?></label>
                                                                <p><?php echo e($settings['bank_name']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Bank Holder Name')); ?></label>
                                                                <p><?php echo e($settings['bank_holder_name']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Bank Account Number')); ?></label>
                                                                <p><?php echo e($settings['bank_account_number']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Bank IFSC Code')); ?></label>
                                                                <p><?php echo e($settings['bank_ifsc_code']); ?></p>
                                                            </div>
                                                        </div>
                                                        <?php if(!empty($settings['bank_other_details'])): ?>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="card-name-on"
                                                                           class="form-label text-dark"><?php echo e(__('Bank Other Details')); ?></label>
                                                                    <p><?php echo e($settings['bank_other_details']); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark"><?php echo e(__('Amount')); ?></label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                                       placeholder="<?php echo e(__('Enter Amount')); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Attachment')); ?></label>
                                                                <input type="file" name="receipt" id="receipt"
                                                                       class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="notes" class="form-label text-dark"><?php echo e(__('Notes')); ?></label>
                                                                <input type="text" name="notes" id="amount"
                                                                       class="form-control "
                                                                       value=""
                                                                       placeholder="<?php echo e(__('Enter notes')); ?>" >
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 ">
                                                            <input type="submit" value="<?php echo e(__('Pay')); ?>" class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET'])): ?>
                                <div class="tab-pane fade " id="stripe_payment">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="<?php echo e(route('invoice.stripe.payment',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                                    method="post" class="require-validation" id="stripe-payment">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark"><?php echo e(__('Amount')); ?></label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                                       placeholder="<?php echo e(__('Enter Amount')); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark"><?php echo e(__('Card Name')); ?></label>
                                                                <input type="text" name="name" id="card-name-on"
                                                                       class="form-control required"
                                                                       placeholder="<?php echo e(__('Card Holder Name')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="card-name-on"
                                                                   class="form-label text-dark"><?php echo e(__('Card Details')); ?></label>
                                                            <div id="card-element">
                                                            </div>
                                                            <div id="card-errors" role="alert"></div>
                                                        </div>
                                                        <div class="col-sm-12 mt-15">

                                                            <input type="submit" value="<?php echo e(__('Pay Now')); ?>"
                                                                   class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($settings['paypal_payment'] == 'on' && !empty($settings['paypal_client_id']) && !empty($settings['paypal_secret_key'])): ?>
                                <div class="tab-pane fade" id="paypal_payment">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="<?php echo e(route('invoice.paypal',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                                    method="post" class="require-validation">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark"><?php echo e(__('Amount')); ?></label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                                       placeholder="<?php echo e(__('Enter Amount')); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 ">
                                                            <input type="submit" value="<?php echo e(__('Pay Now')); ?>"
                                                                   class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <img class="img-fluid invoice-logo"
                                             src=" <?php echo e(asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')); ?>"
                                             alt="invoice-logo">
                                    </a>
                                    <a class="codexdark-logo" href="Javascript:void(0);">
                                        <img class="img-fluid invoice-logo"
                                             src=" <?php echo e(asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')); ?>"
                                             alt="invoice-logo">
                                    </a>
                                </div>
                                <ul class="contact-list">

                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-user"></i>
                                        </div><?php echo e($settings['company_name']); ?>

                                    </li>
                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-phone"></i>
                                        </div><?php echo e($settings['company_phone']); ?>

                                    </li>
                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-envelope"></i>
                                        </div><?php echo e($settings['company_email']); ?>

                                    </li>

                                </ul>
                            </div>
                            <div class="invoice-user">
                                <div class="left-user">
                                    <h5><?php echo e(__('Inovice to')); ?>:</h5>
                                    <ul class="detail-list">
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-user"></i>
                                            </div><?php echo e(!empty($tenant) && !empty($tenant->user)?$tenant->user->first_name.' '.$tenant->user->last_name:''); ?>

                                        </li>
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-phone"></i>
                                            </div><?php echo e(!empty($tenant) && !empty($tenant->user) ?$tenant->user->phone_number:'-'); ?>

                                        </li>
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-map-marker"></i></div>
                                            <?php echo e(!empty($tenant)?$tenant->address:''); ?>

                                        </li>
                                    </ul>
                                </div>
                                <div class="right-user">
                                    <ul class="detail-list">
                                        <li><?php echo e(__('Status')); ?>:
                                            <?php if($invoice->status=='open'): ?>
                                                <span
                                                    class="badge badge-primary"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status=='paid'): ?>
                                                <span
                                                    class="badge badge-success"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status=='partial_paid'): ?>
                                                <span
                                                    class="badge badge-warning"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php endif; ?>
                                        </li>
                                        <li><?php echo e(__('Invoice No')); ?>: <span><?php echo e(invoicePrefix().$invoice->invoice_id); ?> </span>
                                        </li>
                                        <li><?php echo e(__('Invoice Month')); ?>:
                                            <span> <?php echo e(date('F Y',strtotime($invoice->invoice_month))); ?> </span></li>
                                        <li><?php echo e(__('End Date')); ?>: <span><?php echo e(dateFormat($invoice->end_date)); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="body-invoice">
                                <div class="table-responsive1">
                                    <table class="table ml-1">
                                        <thead>
                                        <tr>
                                            <th><?php echo e(__('Type')); ?></th>
                                            <th><?php echo e(__('Description')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $invoice->types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(!empty($type->types)?$type->types->title:'-'); ?></td>
                                                <td><?php echo e($type->description); ?></td>
                                                <td><?php echo e(priceFormat($type->amount)); ?></td>
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
                                        <td><?php echo e(priceFormat($invoice->getInvoiceSubTotalAmount())); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(__('Due Amount')); ?></td>
                                        <td><?php echo e(priceFormat($invoice->getInvoiceDueAmount())); ?> </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Payment History')); ?></h5>
                    </div>
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
                                                <a href="<?php echo e($payment->receipt); ?>" target="_blank"
                                                ><i data-feather="eye"></i></a>
                                            <?php else: ?>
                                                <a href="<?php echo e(asset(Storage::url('upload/receipt')).'/'.$payment->receipt); ?>"
                                                   download="download"><i data-feather="download"></i></a>
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
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/show.blade.php ENDPATH**/ ?>