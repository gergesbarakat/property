<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Invoice Details')); ?>

<?php $__env->stopSection(); ?>

<?php
    $admin_logo = getSettingsValByName('company_logo');
    $settings = settings();
    $logo_path =
        asset(Storage::url('upload/logo/')) .
        '/' .
        (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png');
?>

<?php $__env->startPush('script-page'); ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Print functionality
        $(document).on('click', '.print-invoice', function() {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            // Add a class to the body for print-specific styles
            document.body.classList.add('invoice-print-mode');

            window.print();

            // Revert back to original content after printing
            document.body.innerHTML = originalContents;
            document.body.classList.remove('invoice-print-mode'); // Remove the class
            // Re-attach event listeners if necessary, depending on your SPA setup
        });

        // Stripe Payment Integration
        <?php if(
            $invoicePaymentSettings['STRIPE_PAYMENT'] == 'on' &&
                !empty($invoicePaymentSettings['STRIPE_KEY']) &&
                !empty($invoicePaymentSettings['STRIPE_SECRET'])): ?>
            var stripe = Stripe('<?php echo e($invoicePaymentSettings['STRIPE_KEY']); ?>');
            var elements = stripe.elements();
            var style = {
                base: {
                    fontSize: '16px', // Slightly larger font for better readability
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a',
                },
            };
            var card = elements.create('card', {
                style: style
            });
            card.mount('#card-element');

            var form = document.getElementById('stripe-payment');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $('#card-errors').html(result.error.message)
                            .show(); // Display errors in the dedicated div
                        $.NotificationApp.send("Error", result.error.message, "top-right",
                            "rgba(0,0,0,0.2)", "error");
                    } else {
                        var token = result.token;
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', token.id);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        <?php endif; ?>
    </script>

    
    <style>
        /* These styles will apply when printing */
        @media print {

            html,
            body {
                height: 100%;
                /* Make HTML and Body take full height */
                margin: 0 !important;
                padding: 0 !important;
                /* Optional: Adjust font size for print for better readability */
                font-size: 11pt;
            }

            /* Ensure the content itself stretches to fill space */
            #invoice-print {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                /* Minimum viewport height */
                justify-content: space-between;
                /* Pushes footer to bottom */
            }

            #invoice-print>.row {
                flex-grow: 1;
                /* Allow the content row to expand */
                display: flex;
                flex-direction: column;
            }

            #invoice-print .card {
                flex-grow: 1;
                /* Allow the card to expand */
                display: flex;
                flex-direction: column;
            }

            #invoice-print .card-body {
                flex-grow: 1;
                /* Allow the card body to expand */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* Space out elements within card body */
            }

            .invoice-container {
                flex-grow: 1;
                /* Allow the container to take available space */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* Pushes footer to bottom */
            }

            /* Add space between the last table and the footer */
            .invoice-items+.row.justify-content-end {
                margin-bottom: 2rem !important;
                /* Add more space above the totals table */
            }

            .invoice-footer {
                margin-top: auto;
                /* Pushes the footer to the bottom of the flex container */
                padding-top: 1.5rem !important;
                /* Adjust as needed */
                border-top: 1px solid #dee2e6;
                /* Ensure border is visible on print */
            }

            /* Hide elements not needed for printing */
            .mb-4,
            /* Actions row */
            .collapse,
            /* Payment options */
            .breadcrumb-item,
            /* Breadcrumbs */
            .header-navbar,
            /* Your main navigation */
            .sidebar,
            /* Your sidebar */
            .footer-main

            /* Your application's main footer, if separate */
                {
                display: none !important;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('invoice.index')); ?>"><?php echo e(__('Invoice')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Details')); ?></li>
        </ol>
    </nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mb-4" style="height: 100%">
        <div class="col-12 text-end">
            <a class="btn btn-primary print-invoice me-2" href="javascript:void(0);">
                <i class="ti ti-printer me-1"></i> <?php echo e(__('Print Invoice')); ?>

            </a>
            <?php if($invoice->status != 'paid'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice payment')): ?>
                    <?php if(\Auth::user()->type == 'tenant'): ?>
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#paymentModal"
                            aria-expanded="false" aria-controls="paymentModal">
                            <i class="ti ti-cash me-1"></i> <?php echo e(__('Make Payment')); ?>

                        </button>
                    <?php else: ?>
                        <a class="btn btn-success customModal" href="#" data-size="md"
                            data-url="<?php echo e(route('invoice.payment.create', $invoice->id)); ?>"
                            data-title="<?php echo e(__('Add Payment')); ?>">
                            <i class="ti ti-cash me-1"></i> <?php echo e(__('Add Payment')); ?>

                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if($invoice->status != 'paid' && \Auth::user()->type == 'tenant'): ?>
        <div class="collapse mb-4" id="paymentModal">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Payment Options')); ?></h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <?php if($settings['bank_transfer_payment'] == 'on'): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-bank-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-bank-transfer" type="button" role="tab"
                                    aria-controls="pills-bank-transfer"
                                    aria-selected="true"><?php echo e(__('Bank Transfer')); ?></button>
                            </li>
                        <?php endif; ?>
                        <?php if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET'])): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo e($settings['bank_transfer_payment'] != 'on' ? 'active' : ''); ?>"
                                    id="pills-stripe-tab" data-bs-toggle="pill" data-bs-target="#pills-stripe-payment"
                                    type="button" role="tab" aria-controls="pills-stripe-payment"
                                    aria-selected="<?php echo e($settings['bank_transfer_payment'] != 'on' ? 'true' : 'false'); ?>"><?php echo e(__('Stripe')); ?></button>
                            </li>
                        <?php endif; ?>
                        <?php if(
                            $settings['paypal_payment'] == 'on' &&
                                !empty($settings['paypal_client_id']) &&
                                !empty($settings['paypal_secret_key'])): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-paypal-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-paypal-payment" type="button" role="tab"
                                    aria-controls="pills-paypal-payment" aria-selected="false"><?php echo e(__('Paypal')); ?></button>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        
                        <?php if($settings['bank_transfer_payment'] == 'on'): ?>
                            <div class="tab-pane fade <?php echo e($settings['bank_transfer_payment'] == 'on' ? 'show active' : ''); ?>"
                                id="pills-bank-transfer" role="tabpanel" aria-labelledby="pills-bank-tab">
                                <form
                                    action="<?php echo e(route('invoice.banktransfer.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                    method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark"><?php echo e(__('Bank Name')); ?>:</label>
                                            <p class="form-control-static"><?php echo e($settings['bank_name'] ?? '-'); ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark"><?php echo e(__('Bank Holder Name')); ?>:</label>
                                            <p class="form-control-static"><?php echo e($settings['bank_holder_name'] ?? '-'); ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark"><?php echo e(__('Bank Account Number')); ?>:</label>
                                            <p class="form-control-static"><?php echo e($settings['bank_account_number'] ?? '-'); ?>

                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-dark"><?php echo e(__('Bank IFSC Code')); ?>:</label>
                                            <p class="form-control-static"><?php echo e($settings['bank_ifsc_code'] ?? '-'); ?></p>
                                        </div>
                                        <?php if(!empty($settings['bank_other_details'])): ?>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label text-dark"><?php echo e(__('Bank Other Details')); ?>:</label>
                                                <p class="form-control-static"><?php echo e($settings['bank_other_details']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-md-6 mb-3">
                                            <label for="amount" class="form-label text-dark"><?php echo e(__('Amount')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount" class="form-control"
                                                value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                placeholder="<?php echo e(__('Enter Amount')); ?>" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="receipt" class="form-label text-dark"><?php echo e(__('Attachment')); ?>

                                                <span class="text-danger">*</span></label>
                                            <input type="file" name="receipt" id="receipt" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="notes"
                                                class="form-label text-dark"><?php echo e(__('Notes')); ?></label>
                                            <input type="text" name="notes" id="notes" class="form-control"
                                                placeholder="<?php echo e(__('Enter notes')); ?>">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary"><?php echo e(__('Pay')); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET'])): ?>
                            <div class="tab-pane fade <?php echo e($settings['bank_transfer_payment'] != 'on' ? 'show active' : ''); ?>"
                                id="pills-stripe-payment" role="tabpanel" aria-labelledby="pills-stripe-tab">
                                <form
                                    action="<?php echo e(route('invoice.stripe.payment', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                    method="post" id="stripe-payment">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="amount_stripe" class="form-label text-dark"><?php echo e(__('Amount')); ?>

                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount_stripe" class="form-control"
                                                value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                placeholder="<?php echo e(__('Enter Amount')); ?>" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="card-name-on"
                                                class="form-label text-dark"><?php echo e(__('Card Holder Name')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="card-name-on" class="form-control"
                                                placeholder="<?php echo e(__('Card Holder Name')); ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="card-element"
                                                class="form-label text-dark"><?php echo e(__('Card Details')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <div id="card-element" class="form-control">
                                            </div>
                                            <div id="card-errors" class="text-danger mt-2" role="alert"
                                                style="display: none;"></div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary"><?php echo e(__('Pay Now')); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(
                            $settings['paypal_payment'] == 'on' &&
                                !empty($settings['paypal_client_id']) &&
                                !empty($settings['paypal_secret_key'])): ?>
                            <div class="tab-pane fade" id="pills-paypal-payment" role="tabpanel"
                                aria-labelledby="pills-paypal-tab">
                                <form
                                    action="<?php echo e(route('invoice.paypal', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))); ?>"
                                    method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="amount_paypal" class="form-label text-dark"><?php echo e(__('Amount')); ?>

                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount_paypal" class="form-control"
                                                value="<?php echo e($invoice->getInvoiceDueAmount()); ?>"
                                                placeholder="<?php echo e(__('Enter Amount')); ?>" required min="0.01"
                                                step="0.01">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary"><?php echo e(__('Pay Now')); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div id="invoice-print" style="height: 100%">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-container">
                            
                            <div class="logo-area">
                                <a href="Javascript:void(0);" class="d-block">
                                    <img class="img-fluid invoice-logo" src="<?php echo e($logo_path); ?>" alt="company-logo">
                                </a>
                            </div>
                            <div class="invoice-header d-flex justify-content-between align-items-start mb-4">

                                <div class="invoice-details text-end">
                                    <h2 class="mb-2"><?php echo e(__('Invoice')); ?></h2>
                                    <ul class="list-unstyled">
                                        <li><strong><?php echo e(__('Status')); ?>:</strong>
                                            <?php if($invoice->status == 'open'): ?>
                                                <span
                                                    class="badge bg-primary"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status == 'paid'): ?>
                                                <span
                                                    class="badge bg-success"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status == 'partial_paid'): ?>
                                                <span
                                                    class="badge bg-warning"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php endif; ?>
                                        </li>
                                        <li><strong><?php echo e(__('Invoice No')); ?>:</strong>
                                            <?php echo e(invoicePrefix() . $invoice->invoice_id); ?></li>
                                        <li><strong><?php echo e(__('Invoice Month')); ?>:</strong>
                                            <?php echo e(date('F Y', strtotime($invoice->invoice_month))); ?></li>
                                        <li><strong><?php echo e(__('End Date')); ?>:</strong> <?php echo e(dateFormat($invoice->end_date)); ?>

                                        </li>
                                    </ul>
                                </div>
                                <div class="invoice-recipient mb-5">
                                    <h5 class="mb-3"><?php echo e(__('Invoice to')); ?>:</h5>
                                    <ul class="list-unstyled">
                                        <li><strong><?php echo e(__('Name')); ?>:</strong>
                                            <?php echo e(!empty($tenant) && !empty($tenant->user) ? $tenant->user->first_name . ' ' . $tenant->user->last_name : '-'); ?>

                                        </li>
                                        <li><strong><?php echo e(__('Phone')); ?>:</strong>
                                            <?php echo e(!empty($tenant) && !empty($tenant->user) ? $tenant->user->phone_number : '-'); ?>

                                        </li>
                                        <li><strong><?php echo e(__('Address')); ?>:</strong>
                                            <?php echo e(!empty($tenant) ? $tenant->address : '-'); ?>

                                        </li>
                                        <li><strong><?php echo e(__('National ID')); ?>:</strong>
                                            <?php echo e(!empty($tenant) ? $tenant->zip_code : '-'); ?>

                                        </li>
                                    </ul>
                                </div>
                            </div>


                            <hr class="my-4">


                            <div class="invoice-items mt-100 mb-5">
                                 <div class="table-responsive">
                                    <table class="table border table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Transaction Id')); ?></th>
                                                <th><?php echo e(__('Payment Date')); ?></th>
                                                <th><?php echo e(__('Amount')); ?></th>
                                                <th><?php echo e(__('Type')); ?></th>
                                                <th><?php echo e(__('Notes')); ?></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($payment->transaction_id); ?></td>
                                                    <td><?php echo e(dateFormat($payment->payment_date)); ?></td>
                                                    <td><?php echo e(priceFormat($payment->amount)); ?></td>
                                                    <td><?php echo e(__($payment->payment_type)); ?></td>
                                                    <td><?php echo e($payment->notes ?? '-'); ?></td>
                                                    
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center"><?php echo e(__('No payments found.')); ?>

                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row justify-content-end invoice-totals-section"> 
                                <div class="col-md-5">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                
                                                <tr>
                                                     <strong><?php echo e(__('Paid Amount')); ?>:</strong>
                                                         <?php echo e(priceFormat($invoice->getInvoicePaidAmount())); ?>

                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="invoice-footer text-center mt-100 pt-4 border-top">
                                <ul
                                    class="list-unstyled contact-list d-flex justify-content-center align-items-center mb-0">
                                    <li class="mx-3">
                                        <i class="ti ti-user me-1"></i><?php echo e($settings['company_name'] ?? '-'); ?>

                                    </li>
                                    <li class="mx-3">
                                        <i class="ti ti-phone me-1"></i><?php echo e($settings['company_phone'] ?? '-'); ?>

                                    </li>
                                    <li class="mx-3">
                                        <i class="ti ti-mail me-1"></i><?php echo e($settings['company_email'] ?? '-'); ?>

                                    </li>
                                    
                                </ul>
                                <p class="mt-2 text-muted">&copy; <?php echo e(date('Y')); ?>

                                    <?php echo e($settings['company_name'] ?? 'Your Company'); ?>. All rights reserved.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\JOWEB\property\resources\views/invoice/show.blade.php ENDPATH**/ ?>