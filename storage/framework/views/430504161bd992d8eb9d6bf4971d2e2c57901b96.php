<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo e(__('Invoice')); ?> <?php echo e($invoice->invoice_id); ?></title>
    <style>
        /* ✅ FIX: Using a font that supports Arabic characters is crucial */
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header-table { width: 100%; margin-bottom: 25px; }
        .header-table td { vertical-align: top; }
        .company-logo { width: 150px; }
        .company-details { text-align: left; } /* Aligned left for RTL */
        .company-details h1 { margin: 0; font-size: 28px; color: #000; }
        .info-table { width: 100%; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .info-table .label { font-weight: bold; width: 100px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { text-align: right; padding: 10px; border-bottom: 1px solid #eee; } /* Aligned right for RTL */
        .items-table th:last-child, .items-table td:last-child { text-align: left; }
        .items-table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .items-table .total-row td { border-top: 2px solid #333; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #777; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>
    <?php
        $admin_logo = getSettingsValByName('company_logo');
        $settings = settings();
    ?>

    <div class="container">
        <table class="header-table">
            <tr>
                <td>
                    <?php
                        $logoPath = isset($admin_logo) && !empty($admin_logo) ? storage_path('app/public/upload/logo/' . $admin_logo) : null;
                    ?>
                    <?php if($logoPath && file_exists($logoPath)): ?>
                        <img src="<?php echo e($logoPath); ?>" alt="Company Logo" class="company-logo">
                    <?php endif; ?>
                </td>
                <td class="company-details">
                    <h1><?php echo e(__('فاتورة')); ?></h1>
                    <p>
                        <strong><?php echo e(__('رقم الفاتورة:')); ?></strong> <?php echo e($invoice->invoice_id); ?><br>
                        <strong><?php echo e(__('التاريخ:')); ?></strong> <?php echo e(\Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d')); ?>

                    </p>
                </td>
            </tr>
        </table>
        <hr>
        <div class="section">
             <table class="info-table">
                <tr>
                    <td class="label"><?php echo e(__('فاتورة إلى:')); ?></td>
                    <td>
                        <strong><?php echo e($tenant?->user?->first_name); ?> <?php echo e($tenant?->user?->last_name); ?></strong><br>
                        <?php echo e($tenant?->address ?? '-'); ?><br>
                        <?php echo e($tenant?->user?->email ?? '-'); ?>

                    </td>
                    <td class="label"><?php echo e(__('بيانات الشركة:')); ?></td>
                     <td>
                        <strong><?php echo e($settings['company_name'] ?? ''); ?></strong><br>
                        <?php echo e($settings['company_address'] ?? ''); ?><br>
                        <?php echo e($settings['company_email'] ?? ''); ?>

                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th><?php echo e(__('الوصف')); ?></th>
                        <th style="text-align: left;"><?php echo e(__('المبلغ')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php echo e($item->description); ?><br>
                                <small><?php echo e(__(ucfirst($item->invoice_type))); ?></small>
                            </td>
                            <td style="text-align: left;">$<?php echo e(number_format($item->amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="total-row">
                        <td style="text-align: left;"><strong><?php echo e(__('الإجمالي')); ?></strong></td>
                        <td style="text-align: left;">
                            <strong>$<?php echo e(number_format($invoice->items->sum('amount'), 2)); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><?php echo e(__('شكراً لتعاملكم معنا!')); ?></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\JOWEB\property\resources\views/pdf/invoice_details.blade.php ENDPATH**/ ?>