<!DOCTYPE html>
<html lang="ar">
<?php
    $settings = settings();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @theme {
            --font-display: "Cairo", "sans-serif";
        }

        body {
            font-family: 'Cairo', sans-serif;
            padding: 100px;
            display: grid;
            width: 890px;
            align-items: center;
            justify-content: center;

        }



        .invoice-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 890px;

        }

        .invoice-header {
            display: flex;
            flex-direction: row;
            width: 890px;

            flex-wrap: nowrap;
            align-items: center;
            /* vertical alignment */
            justify-content: space-between;
            /* optional: space between image and text */
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .company-info {
            text-align: left;
            margin-bottom: 20px;
        }

        .client-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-bottom: 20px;
            font-size: medium;
        }

        table {}

        th,
        td {
            word-wrap: break-word !important;
            white-space: normal !important;
            word-break: break-all !important;
            width: <?php echo e($col_width); ?>px !important;
            border: 1px solid #ddd !important;
            max-width: <?php echo e($col_width); ?>px !important;

            padding: 6px !important;
            font-size: 12px !important;
        }

        th {
            background-color: #f0f0f0;
        }

        .total-amount {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        @media print {
            .invoice-container {
                margin: 0;
                width: 890px;

                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-header">

        <div style="display:flex;flex-direction:column;">
            <h1 class="invoice-title">Invoice</h1>
            <p>Washer<br>Address<br>City, State, Zip</p>
            Invoice Number: <?php echo e($invoiceid); ?><br>
            Date: <?php echo e($invoicedate); ?><br>
        </div>
        <div style="align-items:flex-end">
            <img style="width:200px" src="<?php echo e(asset(Storage::url('upload/logo')) . '/' . $settings['company_favicon']); ?>"
                alt="">

        </div>

    </div>

    <div class="invoice-details">
        <?php
            $styledTable = str_replace(
                ['<td', '<th'],
                [
                    '<td style="width: ' .
                    $col_width .
                    'px; word-wrap: break-word; white-space: normal; padding: 6px; border: 1px solid #ccc;"',
                    '<th style="width: ' .
                    $col_width .
                    'px; word-wrap: break-word; white-space: normal; padding: 6px; border: 1px solid #ccc;"',
                ],
                $table_html,
            );
        ?>

        <?php echo $styledTable; ?>


    </div>

    <div class="total-amount">
        <p>Total: $<?php echo e($total); ?></p>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
    </div>
</body>

</html>
<?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/invoice.blade.php ENDPATH**/ ?>