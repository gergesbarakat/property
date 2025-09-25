<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/apexcharts.min.js')); ?>"></script>
    <script>
        (function() {
            var options = {
                series: [{
                    name: "<?php echo e(__('Income')); ?>",
                    type: 'column',
                    data: <?php echo json_encode($result['incomeExpenseByMonth']['income']); ?>,
                }, {
                    name: " <?php echo e(__('Expense')); ?>",
                    type: 'area',
                    data: <?php echo json_encode($result['incomeExpenseByMonth']['expense']); ?>,
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [0, 2],
                    curve: 'smooth',
                },
                plotOptions: {
                    bar: {
                        columnWidth: "20%",
                        borderRadius: 5,
                    }
                },
                fill: {
                    opacity: [1, 0.1],
                },
                colors: ['#5c6ac4', '#5c6ac4'],
                yaxis: {
                    labels: {
                        formatter: function(y) {
                            return "<?php echo e($result['settings']['CURRENCY_SYMBOL'] ?? '$'); ?>" + y.toFixed(0);
                        },
                    },
                },
                xaxis: {
                    categories: <?php echo json_encode($result['incomeExpenseByMonth']['label']); ?>,
                },
            };
            var chart = new ApexCharts(document.querySelector("#incomeExpense"), options);
            chart.render();

            // --- Upcoming Installments Filter Logic ---
            $('.installment-filter-btn').on('click', function() {
                $('.installment-filter-btn').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                var target = $(this).data('target');
                $('.installments-list').hide();
                $('#' + target).show();
            });
        })();
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">
                <h1><?php echo e(__('Dashboard')); ?></h1>
            </a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-primary-lighten text-primary rounded"><i
                                        class="fa fa-building"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1"><?php echo e(__('Total Property')); ?></p>
                            <h4 class="mb-0"><?php echo e($result['totalProperty']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-primary-lighten text-primary rounded"><i
                                        class="fa fa-home"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1"><?php echo e(__('Total Unit')); ?></p>
                            <h4 class="mb-0"><?php echo e($result['totalUnit']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-success-lighten text-success rounded"><i
                                        class="fa fa-money-bill-wave"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1"><?php echo e(__('Total Income')); ?></p>
                            <h4 class="mb-0">
                                <?php echo e($result['settings']['CURRENCY_SYMBOL'] ?? '$'); ?><?php echo e($result['totalIncome']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-danger-lighten text-danger rounded"><i
                                        class="fa fa-arrow-circle-down"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1"><?php echo e(__('Total Expense')); ?></p>
                            <h4 class="mb-0">
                                <?php echo e($result['settings']['CURRENCY_SYMBOL'] ?? '$'); ?><?php echo e($result['totalExpense']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo e(__('Upcoming Installment Payments')); ?></h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-sm installment-filter-btn"
                            data-target="week-installments"><?php echo e(__('This Week')); ?></button>
                        <button type="button" class="btn btn-outline-primary btn-sm installment-filter-btn"
                            data-target="month-installments"><?php echo e(__('This Month')); ?></button>
                    </div>
                </div>
                <div class="card-body">
                    
                    <ul class="list-group list-group-flush installments-list" id="week-installments">
                        <?php $__empty_1 = true; $__currentLoopData = $dueThisWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    
                                    <a href="<?php echo e(route('tenant.show', $installment->buyer->id)); ?>" class="text-dark">
                                        <strong><?php echo e(optional($installment->buyer->user)->first_name); ?></strong>
                                    </a>
                                    <small
                                        class="d-block text-muted"><?php echo e(optional($installment->buyer->propertyUnit->property)->name); ?>

                                        - <?php echo e(optional($installment->buyer->propertyUnit)->name); ?></small>
                                </div>
                                <div class="text-end">
                                    <strong
                                        class="text-dark"><?php echo e($result['settings']['CURRENCY_SYMBOL'] ?? '$'); ?><?php echo e(number_format($installment->amount, 2)); ?></strong>
                                    <small class="d-block text-muted">Due:
                                        <?php echo e(\Carbon\Carbon::parse($installment->due_date)->format('D, M j')); ?></small>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="list-group-item text-center text-muted"><?php echo e(__('No payments due this week.')); ?></li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="list-group list-group-flush installments-list" id="month-installments"
                        style="display: none;">
                        <?php $__empty_1 = true; $__currentLoopData = $dueThisMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    
                                    <a href="<?php echo e(route('tenant.show', $installment->buyer->id)); ?>" class="text-dark">
                                        <strong><?php echo e(optional($installment->buyer->user)->first_name); ?></strong>
                                    </a>
                                    <small
                                        class="d-block text-muted"><?php echo e(optional($installment->buyer->propertyUnit->property)->name); ?>

                                        - <?php echo e(optional($installment->buyer->propertyUnit)->name); ?></small>
                                </div>
                                <div class="text-end">
                                    <strong
                                        class="text-dark"><?php echo e($result['settings']['CURRENCY_SYMBOL'] ?? '$'); ?><?php echo e(number_format($installment->amount, 2)); ?></strong>
                                    <small class="d-block text-muted">Due:
                                        <?php echo e(\Carbon\Carbon::parse($installment->due_date)->format('D, M j')); ?></small>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="list-group-item text-center text-muted"><?php echo e(__('No payments due this month.')); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo e(__('Income Vs Expense')); ?></h4>
                </div>
                <div class="card-body">
                    <div id="incomeExpense"></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\JOWEB\property\resources\views/dashboard/index.blade.php ENDPATH**/ ?>