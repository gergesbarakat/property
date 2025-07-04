<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php
    $admin_logo = getSettingsValByName('company_logo');
    $settings = settings();
?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '.print', function() {
    const elementToCapture = document.getElementById('invoice-print');
    const invoiceId = "<?php echo e($invoice->id); ?>"; // Get the invoice ID from Blade
    const fileName = 'invoice-<?php echo e(invoicePrefix() . $invoice->invoice_id); ?>.pdf';

    html2canvas(elementToCapture, { scale: 2, useCORS: true }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/png');

        // Create a temporary form to submit the data via POST
        var form = document.createElement('form');
        form.method = 'POST';
        // ✅ The action now points to the same route used for template PDFs
        form.action = "<?php echo e(route('pdf.download', ['type' => 'invoice', 'id' => $invoice->id])); ?>";
        form.target = '_blank';

        // Add CSRF token
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrfInput);

        // Add image data
        var imageDataInput = document.createElement('input');
        imageDataInput.type = 'hidden';
        imageDataInput.name = 'imageData';
        imageDataInput.value = imgData;
        form.appendChild(imageDataInput);

        // Add filename
        var fileNameInput = document.createElement('input');
        fileNameInput.type = 'hidden';
        fileNameInput.name = 'filename';
        fileNameInput.value = fileName;
        form.appendChild(fileNameInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
});

    </script>
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
        <?php if(
            $invoicePaymentSettings['STRIPE_PAYMENT'] == 'on' &&
                !empty($invoicePaymentSettings['STRIPE_KEY']) &&
                !empty($invoicePaymentSettings['STRIPE_SECRET'])): ?>
            var stripe_key = Stripe('<?php echo e($invoicePaymentSettings['STRIPE_KEY']); ?>');
            var stripe_elements = stripe_key.elements();
            var strip_css = {
                base: {
                    fontSize: '14px',
                    color: '#32325d',
                },
            };
            var stripe_card = stripe_elements.create('card', {
                style: strip_css
            });
            stripe_card.mount('#card-element');

            var stripe_form = document.getElementById('stripe-payment');
            stripe_form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe_key.createToken(stripe_card).then(function(result) {
                    if (result.error) {
                        $("#stripe_card_errors").html(result.error.message);
                        $.NotificationApp.send("Error", result.error.message, "top-right",
                            "rgba(0,0,0,0.2)", "error");
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
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
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
            <?php if($invoice->status != 'paid'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice payment')): ?>
                    <?php if(\Auth::user()->type == 'tenant'): ?>
                        <a class="btn btn-primary float-end me-2 collapsed" data-bs-toggle="collapse" href="#paymentModal"
                            role="button" aria-expanded="false" aria-controls="collapse1"><?php echo e(__('Payment')); ?></a>
                    <?php else: ?>
                        <a class="btn btn-primary float-end me-2 customModal" href="#" data-size="md"
                            data-url="<?php echo e(route('invoice.payment.create', $invoice->id)); ?>"
                            data-title="<?php echo e(__('Add Payment')); ?>">
                            <?php echo e(__('Add Payment')); ?></a>
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
                                <li><a class="btn " data-bs-toggle="tab" href="#stripe_payment"><?php echo e(__('Stripe')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if(
                                $settings['paypal_payment'] == 'on' &&
                                    !empty($settings['paypal_client_id']) &&
                                    !empty($settings['paypal_secret_key'])): ?>
                                <li><a class="btn" data-bs-toggle="tab" href="#paypal_payment"><?php echo e(__('Paypal')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content">
                            <?php if($settings['bank_transfer_payment'] == 'on'): ?>
                                <div class="tab-pane fade active show" id="bank_transfer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="<?php echo e(route('invoice.banktransfer.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
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
                                                                <label for="notes"
                                                                    class="form-label text-dark"><?php echo e(__('Notes')); ?></label>
                                                                <input type="text" name="notes" id="amount"
                                                                    class="form-control " value=""
                                                                    placeholder="<?php echo e(__('Enter notes')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 ">
                                                            <input type="submit" value="<?php echo e(__('Pay')); ?>"
                                                                class="btn btn-primary">
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
                                                    action="<?php echo e(route('invoice.stripe.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
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
                            <?php if(
                                $settings['paypal_payment'] == 'on' &&
                                    !empty($settings['paypal_client_id']) &&
                                    !empty($settings['paypal_secret_key'])): ?>
                                <div class="tab-pane fade" id="paypal_payment">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="<?php echo e(route('invoice.paypal', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
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
                                        <img class="img-fluid invoice-logo" width="100px" style="width:200px"
                                            src=" <?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>"
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

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5><?php echo e(__('Invoice Details')); ?></h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><?php echo e(__('Status')); ?></th>
                                            <td>
                                                <?php if($invoice->status == 'open'): ?>
                                                    <span class="badge badge-primary"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                                <?php elseif($invoice->status == 'paid'): ?>
                                                    <span class="badge badge-success"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                                <?php elseif($invoice->status == 'partial_paid'): ?>
                                                    <span class="badge badge-warning"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Invoice No')); ?></th>
                                            <td><?php echo e(invoicePrefix() . $invoice->invoice_id); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Invoice Month')); ?></th>
                                            <td><?php echo e(date('F Y', strtotime($invoice->invoice_month))); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('End Date')); ?></th>
                                            <td><?php echo e(dateFormat($invoice->end_date)); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <h5><?php echo e(__('Tenant Details')); ?></h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><?php echo e(__('Name')); ?></th>
                                            <td><?php echo e(!empty($tenant) && !empty($tenant->user) ? $tenant->user->first_name . ' ' . $tenant->user->last_name : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Email')); ?></th>
                                            <td><?php echo e(!empty($tenant) && !empty($tenant->user) ? $tenant->user->email : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Phone')); ?></th>
                                            <td><?php echo e(!empty($tenant) && !empty($tenant->user) ? $tenant->user->phone_number : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Address')); ?></th>
                                            <td><?php echo e(!empty($tenant) ? $tenant->address : '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <h5><?php echo e(__('Property & Unit Details')); ?></h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><?php echo e(__('Property')); ?></th>
                                            <td><?php echo e(!empty($property) ? $property->name : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Unit')); ?></th>
                                            <td><?php echo e(!empty($unit) ? $unit->name : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Unit Type')); ?></th>
                                            <td><?php echo e(!empty($unit) && !empty($unit->type) ? $unit->type->name : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo e(__('Unit Size')); ?></th>
                                            <td><?php echo e(!empty($unit) ? $unit->size : '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="body-invoice">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 align-middle text-center"
                                        style="min-width: 700px;">
                                        <thead>
                                            <tr class="table-primary">
                                                <th><?php echo e(__('Item')); ?></th>
                                                <th><?php echo e(__('Description')); ?></th>
                                                <th><?php echo e(__('Amount')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(stripos($item->name, 'unit details') !== false): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo nl2br(e($item->description)); ?>

                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(stripos($item->name, 'unit details') === false): ?>
                                                    <tr>
                                                        <td><?php echo e($item->name); ?></td>
                                                        <td><?php echo e($item->description); ?></td>
                                                        <td><?php echo e(priceFormat($item->amount)); ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end"><?php echo e(__('Total')); ?></th>
                                                <th><?php echo e(priceFormat($invoice->getInvoiceSubTotalAmount())); ?></th>
                                            </tr>
                                        </tfoot>
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
                    <div class="card-header">
                        <h5><?php echo e(__('Payment History')); ?></h5>
                    </div>
                    <div class="card-body">
                        <table class="display dataTable cell-border datatbl-advance1">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Installment #')); ?></th>
                                    <th><?php echo e(__('Due Date')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Paid Date')); ?></th>
                                    <th><?php echo e(__('Notes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $installments->where('status', 'paid'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($installment->installment_number); ?></td>
                                        <td><?php echo e(dateFormat($installment->due_date)); ?></td>
                                        <td><?php echo e(priceFormat($installment->amount)); ?></td>
                                        <td>
                                            <span class="badge badge-success"><?php echo e(__('Paid')); ?></span>
                                        </td>
                                        <td><?php echo e($installment->created_at ? dateFormat($installment->created_at) : '-'); ?>

                                        </td>
                                        <td><?php echo e($installment->notes); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center"><?php echo e(__('No Paid Installments Found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/show.blade.php ENDPATH**/ ?>