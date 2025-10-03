<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Statement</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header-table,
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-logo {
            width: 150px;
        }

        .company-details {
            text-align: right;
        }

        .company-details h1 {
            margin: 0;
            font-size: 28px;
            color: #000;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f8f8f8;
            padding: 8px;
            border-radius: 5px;
        }

        .info-table td {
            padding: 5px 0;
        }

        .info-table .label {
            font-weight: bold;
            width: 120px;
        }

        .installments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .installments-table th,
        .installments-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .installments-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="section">
            <table class="info-table">
                <tr>
                    <td>
                        <img class="img-fluid" style="width: 150px; height: auto;"
                            src="<?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>"
                            alt="theeme-logo">
                    </td>
                    <td>
                        Bill To:
                        <strong><?php echo e(optional($tenant->user)->first_name); ?>

                            <?php echo e(optional($tenant->user)->last_name); ?></strong><br>
                        <?php echo e($tenant->address ?? '-'); ?><br>
                        <?php echo e($tenant->city ?? ''); ?>, <?php echo e($tenant->state ?? ''); ?> <br>
                        <?php echo e(optional($tenant->user)->email ?? '-'); ?><br>
                        ID:<?php echo e($tenant->zip_code ?? ''); ?>

                    </td>
                    <td>Property Details:
                        <strong><?php echo e(optional($tenant->linked_property)->name ?? '-'); ?></strong><br>
                        Unit: <?php echo e(optional($tenant->propertyUnit)->name ?? '-'); ?><br>
                        Generated on: <?php echo e(now()->format('F j, Y')); ?>

                    </td>
                </tr>
            </table>
        </div>

        
        <div class="section">
            <div class="section-title">Unit Details</div>
            <table class="info-table">
                <tr>
                    <td class="label">Bedrooms:</td>
                    <td><?php echo e(optional($tenant->propertyUnit)->bedroom ?? '-'); ?></td>
                    <td class="label">Baths:</td>
                    <td><?php echo e(optional($tenant->propertyUnit)->baths ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="label">Kitchens:</td>
                    <td><?php echo e(optional($tenant->propertyUnit)->kitchen ?? '-'); ?></td>
                    <td class="label">Status:</td>
                    <td><?php echo e(ucfirst(optional($tenant->propertyUnit)->status) ?? '-'); ?></td>
                </tr>
            </table>
        </div>

        
        <?php if($tenant->installments->isNotEmpty()): ?>
            <div class="section">
                <div class="section-title">Installment Plan</div>
                <table class="installments-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tenant->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($installment->installment_number); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($installment->due_date)->format('M j, Y')); ?></td>
                                <td><?php echo e(number_format($installment->amount, 2)); ?>EGP</td>
                                <td><?php echo e(ucfirst($installment->status)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
<?php /**PATH F:\JOWEB\property\resources\views/pdf/tenant_details.blade.php ENDPATH**/ ?>