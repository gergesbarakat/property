<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    cdxuser-profile
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('tenant.index')); ?>"><?php echo e(__('Tenant')); ?></a></li>
        <li class="breadcrumb-item active"><a href="#"><?php echo e(__('Details')); ?></a></li>
    </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('card-action-btn'); ?>
    <a href="<?php echo e(route('pdf.download', ['type' => 'tenant', 'id' => $tenant->id])); ?>" target="_blank"
        class="btn btn-primary btn-sm">
        <i data-feather="download" class="me-1" style="width:16px; height:16px;"></i>
        <?php echo e(__('Export as PDF')); ?>

    </a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('styles'); ?>
    <style>
        .user-card .user-imgwrap {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }

        .user-card .user-imgwrap img {
            width: 100px;
            height: 100px;
            border: 5px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .user-card .card-body {
            margin-top: 60px;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .financial-summary .stat-card {
            background-color: #f8f9fa;
            border-radius: .5rem;
            padding: 1rem;
            text-align: center;
        }

        .financial-summary .stat-card .icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .financial-summary .stat-card h6 {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .financial-summary .stat-card p {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .info-card .media-body h6 {
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-card .media-body p {
            font-weight: 500;
        }

        .installments-table {
            border-collapse: collapse;
            width: 100%;
        }

        .installments-table thead th {
            font-weight: 600;
            background-color: #fff;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .installments-table td,
        .installments-table th {
            vertical-align: middle !important;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    


    
    <div class="row">
        
        <div class="col-xl-3 cdx-xxl-30 cdx-xl-40">
            <div class="card user-card border-0 shadow-sm">
                <div class="card-header bg-transparent" style="min-height: 50px;"></div>
                <div class="card-body text-center">
                    <div class="user-imgwrap"><img class="img-fluid rounded-circle"
                            src="<?php echo e(optional($tenant->user)->profile ? Storage::url($tenant->user->profile) : asset('path/to/default/avatar.png')); ?>"
                            alt="Profile Image"></div>
                    <div class="user-detailwrap">
                        <h3><?php echo e(optional($tenant->user)->first_name); ?> <?php echo e(optional($tenant->user)->last_name); ?></h3>
                        <h6 class="text-muted"><?php echo e(optional($tenant->user)->email ?? '-'); ?></h6>
                        <h6 class="text-muted"><?php echo e(optional($tenant->user)->phone_number ?? '-'); ?></h6>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-9 cdx-xxl-70 cdx-xl-60">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h4><?php echo e(__('Financial Summary')); ?></h4>
                </div>
                <div class="card-body financial-summary">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-primary"><i data-feather="dollar-sign"></i></div>
                                <h6>Total Amount</h6>
                                <p><?php echo e(number_format($financialSummary['total_amount'], 2)); ?> EGP</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-success"><i data-feather="check-circle"></i></div>
                                <h6>Amount Paid</h6>
                                <p><?php echo e(number_format($financialSummary['paid_amount'], 2)); ?> EGP</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="icon text-danger"><i data-feather="trending-down"></i></div>
                                <h6>Amount Due</h6>
                                <p><?php echo e(number_format($financialSummary['due_amount'], 2)); ?> EGP</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm info-card">
                <div class="card-header bg-transparent">
                    <h4><?php echo e(__('Additional Information')); ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('Property')); ?></h6>
                                    <p><?php echo e(optional($tenant->linked_property)->name ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('Unit')); ?></h6>
                                    <p><?php echo e(optional($tenant->propertyUnit)->name ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('Total Family Member')); ?></h6>
                                    <p><?php echo e($tenant->family_member ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('Country')); ?></h6>
                                    <p><?php echo e($tenant->country ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('State')); ?></h6>
                                    <p><?php echo e($tenant->state ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('City')); ?></h6>
                                    <p><?php echo e($tenant->city ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="media">
                                <div class="media-body">
                                    <h6><?php echo e(__('Zip Code')); ?></h6>
                                    <p><?php echo e($tenant->zip_code ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php if($tenant->installments->isNotEmpty()): ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="media">
                                    <div class="media-body">
                                        <h6><?php echo e(__('Lease Start Date')); ?></h6>
                                        <p><?php echo e(\Carbon\Carbon::parse($tenant->installments->min('due_date'))->format('M j, Y')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="media">
                                    <div class="media-body">
                                        <h6><?php echo e(__('Lease End Date')); ?></h6>
                                        <p><?php echo e(\Carbon\Carbon::parse($tenant->installments->max('due_date'))->format('M j, Y')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h4><?php echo e(__('Installment Plan')); ?></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table installments-table" id="invoice-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    
                                    <th><?php echo e(__('Due Date')); ?></th>

                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $tenant->installments->sortBy('due_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($installment->installment_number); ?></td>
                                        
                                        <td><?php echo e(\Carbon\Carbon::parse($installment->due_date)->format('Y-m')); ?> </td>
                                        <td><?php echo e(number_format($installment->amount, 2)); ?> EGP</td>
                                        <td class="text-center">
                                            <?php if($installment->status == 'paid'): ?>
                                                <span class="badge bg-success text-white">Paid</span>
                                            <?php elseif($installment->status == 'pending'): ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white">Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($installment->status != 'paid'): ?>
                                                <a href="#" class="btn btn-sm btn-outline-success customModal"
                                                    data-url="<?php echo e(route('installment.payment.create', $installment->id)); ?>"
                                                    data-title="Record Payment for Installment #<?php echo e($installment->installment_number); ?>"
                                                    data-size="md">
                                                    Record Payment
                                                </a>
                                            <?php else: ?>
                                                <?php if(optional($installment->invoice)->payment?->receipt): ?>
                                                    <a href="<?php echo e(Storage::url($installment->invoice->payment->receipt)); ?>"
                                                        class="btn btn-sm btn-outline-primary" download>
                                                        <i data-feather="download" style="width:16px; height:16px;"></i>
                                                        Download Receipt
                                                    </a>
                                                <?php else: ?>
                                                    <span>-</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-muted mb-0">No installment plan found.</p>
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

    
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="commonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click', '.customModal', function() {
            var modal = $('#commonModal');
            var url = $(this).data('url');
            var title = $(this).data('title');
            var size = $(this).data('size') || 'md';

            modal.find('.modal-title').text(title);
            modal.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl').addClass('modal-' + size);

            $.get(url, function(data) {
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/tenant/show.blade.php ENDPATH**/ ?>