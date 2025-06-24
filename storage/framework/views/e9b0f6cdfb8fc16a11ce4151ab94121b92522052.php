<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="author" content="<?php echo e(!empty($settings['app_name']) ? $settings['app_name'] : env('APP_NAME')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(!empty($settings['app_name']) ? $settings['app_name'] : env('APP_NAME')); ?> - <?php echo $__env->yieldContent('page-title'); ?> </title>

    <meta name="title" content="<?php echo e($settings['meta_seo_title']); ?>">
    <meta name="keywords" content="<?php echo e($settings['meta_seo_keyword']); ?>">
    <meta name="description" content="<?php echo e($settings['meta_seo_description']); ?>">


    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="og:title" content="<?php echo e($settings['meta_seo_title']); ?>">
    <meta property="og:description" content="<?php echo e($settings['meta_seo_description']); ?>">
    <meta property="og:image" content="<?php echo e(asset(Storage::url('upload/seo')) . '/' . $settings['meta_seo_image']); ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="twitter:title" content="<?php echo e($settings['meta_seo_title']); ?>">
    <meta property="twitter:description" content="<?php echo e($settings['meta_seo_description']); ?>">
    <meta property="twitter:image"
        content="<?php echo e(asset(Storage::url('upload/seo')) . '/' . $settings['meta_seo_image']); ?>">

    <!-- shortcut icon-->
    <link rel="icon" href="<?php echo e(asset(Storage::url('upload/logo')) . '/' . $settings['company_favicon']); ?>"
        type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset(Storage::url('upload/logo')) . '/' . $settings['company_favicon']); ?>"
        type="image/x-icon">
    <!-- Fonts css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">





    <!-- Font awesome -->
    <link href="<?php echo e(asset('assets/css/vendor/font-awesome.css')); ?>" rel="stylesheet">
    <!-- themify icon-->
    <link href="<?php echo e(asset('assets/css/vendor/themify-icons.css')); ?>" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>



    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css">




    <!-- Slick slider-->
    <link href="<?php echo e(asset('assets/css/vendor/slider/slick-slider/slick.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/vendor/slider/slick-slider/slick-theme.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/vendor/select2/select2.css')); ?>" rel="stylesheet">

    <!-- Scrollbar-->

    <link href="<?php echo e(asset('assets/css/vendor/simplebar.css')); ?>" rel="stylesheet">
    <!-- Bootstrap css-->
    <link href="<?php echo e(asset('assets/css/vendor/bootstrap.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('assets/css/vendor/sweetalert/sweetalert2.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldPushContent('css-page'); ?>
    <!-- Custom css-->
    <?php
        $style = $settings['theme_color'] == 'color1' ? 'style.css' : $settings['theme_color'] . '.css';
        if ($settings['color_type'] == 'custom') {
            $style = 'style.css';
        }
    ?>
    <link href="<?php echo e(asset('assets/css/' . $style)); ?>" id="customstyle" rel="stylesheet">

    <link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet">
    




    
    <style>
        .table-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e9ecef;
        }

        .modern-table {
            border-collapse: collapse;
            width: 100%;
        }

        .modern-table thead th {
            font-weight: 600;
            background-color: #fff;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .modern-table td,
        .modern-table th {
            vertical-align: middle !important;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .modern-table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .modern-table .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        /* ✅ CSS for ghost buttons has been removed to allow for colors. */
        .modern-table .action-buttons .btn {
            width: 32px;
            height: 32px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 3px;
        }

        /* Optional: Give a light background and padding to the entire filter section */
        #filters {
            width: 100%;
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Style the form labels */
        #filters .form-label {
            font-weight: 600;
            color: #333;
        }

        /* Enhance form selects */
        #filters .form-select-sm {
            border-radius: 0.375rem;
            border-color: #ced4da;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        #filters .form-select-sm:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.25);
        }

        /* Responsive spacing between filters */
        @media (max-width: 768px) {
            #filters .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .dt-buttons {
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* Style for each button */
        .dt-button {
            background-color: #0d6efd !important;
            /* Bootstrap primary */
            color: #fff !important;
            border: none !important;
            border-radius: 0.375rem;
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            transition: background-color 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Hover effect */
        .dt-button:hover {
            background-color: #0b5ed7 !important;
        }

        /* Active/focus style */
        .dt-button:active,
        .dt-button:focus {
            outline: none !important;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.3) !important;
        }
    </style>

    <script src="<?php echo e(asset('assets/js/vendors/datatable/datatables.js')); ?>"></script>

    
    <script src="<?php echo e(asset('assets/js/vendors/datatable/dataTables.buttons.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendors/datatable/buttons.print.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendors/datatable/jszip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendors/datatable/pdfmake.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendors/datatable/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendors/datatable/buttons.html5.js')); ?>"></script>


</head>
<?php /**PATH E:\JOWEB\property\resources\views/admin/head.blade.php ENDPATH**/ ?>