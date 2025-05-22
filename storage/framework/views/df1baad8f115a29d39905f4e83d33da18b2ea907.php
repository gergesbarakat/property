<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><h1><?php echo e(__('Dashboard')); ?></h1></a>
        </li>

    </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
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
                height: 452,
                type: 'line',
                toolbar:{
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            legend:{
                show:false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [0,0],
                curve: 'smooth',
            },
            plotOptions: {
                bar: {
                    columnWidth:"20%",
                    startingShape:"rounded",
                    endingShape: "rounded",
                }
            },
            fill:{
                opacity:[1, 0.08],
                gradient:{
                    type:"horizontal",
                    opacityFrom:0.5,
                    opacityTo:0.1,
                    stops: [100, 100, 100]
                }
            },
            colors: [Codexdmeki.themeprimary,Codexdmeki.themesecondary],
            states: {
                normal: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                hover: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
            },
            grid:{
                strokeDashArray: 2,
            },

            yaxis:{
                tickAmount: 10 ,
                labels:{
                    formatter: function (y) {
                        return  "<?php echo e($result['settings']['CURRENCY_SYMBOL']); ?>" + y.toFixed(0);
                    },
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },
            },
            xaxis: {
                categories: <?php echo json_encode($result['incomeExpenseByMonth']['label']); ?> ,
                axisTicks: {
                    show:false
                },
                axisBorder:{
                    show:false
                },
                labels:{
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    },
                },
            },
            responsive:[
                {
                    breakpoint: 1441,
                    options:{
                        chart:{
                            height: 445
                        }
                    },
                },
                {
                    breakpoint: 1366,
                    options:{
                        chart:{
                            height: 320
                        }
                    },
                },
            ]
        };
        var chart = new ApexCharts(document.querySelector("#incomeExpense"), options);
        chart.render();
    </script>
<?php $__env->stopPush(); ?>
<?php
$settings=settings();

?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4><?php echo e(__('Total Property')); ?></h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>
                        <span class="count"><?php echo e($result['totalProperty']); ?></span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4><?php echo e(__('Total Unit')); ?></h4>
                </div>
                <div class="card-body progressCounter">
                    <h2>
                        <span class="count"><?php echo e($result['totalUnit']); ?></span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4><?php echo e(__('Total Invoice')); ?></h4>
                </div>
                <div class="card-body progressCounter">
                    <h2><?php echo e($settings['CURRENCY_SYMBOL']); ?><span class="count"><?php echo e($result['totalIncome']); ?></span> </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue">
                <div class="card-header">
                    <h4><?php echo e(__('Total Expense')); ?></h4>
                </div>
                <div class="card-body progressCounter">
                    <h2><?php echo e($settings['CURRENCY_SYMBOL']); ?><span class="count"><?php echo e($result['totalExpense']); ?></span> </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 cdx-xxl-50">
            <div class="card overall-revenuetbl">
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/dashboard/index.blade.php ENDPATH**/ ?>