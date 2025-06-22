<!-- footer start-->
<footer class="codex-footer">
    <p><?php echo e(__('Copyright')); ?> <?php echo e(date('Y')); ?> © <?php echo e(env('APP_NAME')); ?> <?php echo e(__('All rights reserved')); ?>.</p>
</footer>
<!-- footer end-->
<!-- back to top start //-->
<div class="scroll-top"><i class="fa fa-angle-double-up"></i></div>
<!-- back to top end //-->
<!-- main jquery-->

<!-- Theme Customizer-->
<script src="<?php echo e(asset('assets/js/layout-storage.js')); ?>"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="<?php echo e(asset('assets/js/jquery.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/customizer.js')); ?>"></script>
<!-- Feather icons js-->
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather.js')); ?>"></script>
<!-- Bootstrap js-->
<script src="<?php echo e(asset('assets/js/bootstrap.bundle.js')); ?>"></script>
<!-- Scrollbar-->
<script src="<?php echo e(asset('assets/js/vendors/simplebar.js')); ?>"></script>
<!-- apex chart-->
<script src="<?php echo e(asset('assets/js/vendors/chart/apexcharts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/vendors/datatable/datatables.js')); ?>"></script>


<?php if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'owner'): ?>
    <script>
        var public_path = '<?php echo e(asset('assets/css/')); ?>';
        $(".customizer-modal").append('' +
            '<form method="post" action="<?php echo e(route('theme.settings')); ?>"><?php echo e(csrf_field()); ?><div class="customizer-layer"></div>' +
            '<div class="customizer-action bg-primary"><i data-feather="settings"></i>' +
            '</div><div class="theme-cutomizer"> ' +
            '<div class="customizer-header"> <h4><?php echo e(__('Theme Setting')); ?></h4> ' +
            '<div class="close-customizer"><i data-feather="x"></i></div>' +
            '</div>' +
            '<input type="hidden" name="theme_color" id="theme_color" value="<?php echo e($settings['theme_color']); ?>">' +
            '<input type="hidden" name="sidebar_mode" id="sidebar_mode" value="<?php echo e($settings['sidebar_mode']); ?>">' +
            '<input type="hidden" name="layout_direction" id="layout_direction" value="<?php echo e($settings['layout_direction']); ?>">' +
            '<input type="hidden" name="layout_mode" id="layout_mode" value="<?php echo e($settings['layout_mode']); ?>">' +
            '<input type="hidden" name="own_color" id="own_color" value="<?php echo e($settings['own_color']); ?>">' +
            '<input type="hidden" name="own_color_code" id="own_color_code" value="<?php echo e($settings['own_color_code']); ?>">' +
            '<input type="hidden" name="color_type" id="color_type" value="<?php echo e($settings['color_type']); ?>">' +
            '<div class="customizer-body"> ' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title"><?php echo e(__('Theme Color')); ?></h6> ' +
            '<ul class="customizeoption-list themecolor-list" > ' +
            '<li class="color1 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color1' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color2 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color2' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color3 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color3' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color4 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color4' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color5 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color5' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color6 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color6' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color7 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color7' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color8 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color8' ? 'active-mode' : ''); ?>"></li>' +
            '<li class="color9 <?php echo e($settings['color_type'] == 'default' && $settings['theme_color'] == 'color9' ? 'active-mode' : ''); ?>"></li>' +
            '</ul> ' +
            '<ul class="" > ' +
            '<li class="custom-color"><?php echo e(__('Choose Your Own Color')); ?> <input class="" value="<?php echo e($settings['own_color_code']); ?>" id="colorChange" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id7="transparentcolor" ></li>' +
            '</ul> ' +
            '</div>' +

            '<div class="cutomize-group "> ' +
            '<h6 class="customizer-title"><?php echo e(__('Layout mode')); ?></h6> ' +
            '<ul class="customizeoption-list"> ' +
            '<li class="light-action <?php echo e($settings['layout_mode'] == 'lightmode' ? 'active-mode' : ''); ?>"><?php echo e(__('Light')); ?></li>' +
            '<li class="dark-action <?php echo e($settings['layout_mode'] == 'darkmode' ? 'active-mode' : ''); ?>"><?php echo e(__('Dark')); ?></li>' +
            '</ul> ' +
            '</div>' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title"><?php echo e(__('Sidebar Mode')); ?></h6> ' +
            '<ul class="customizeoption-list sidebaroption-list"> ' +
            '<li class="sidebarlight-action <?php echo e($settings['sidebar_mode'] == 'light' ? 'active-mode' : ''); ?>"><?php echo e(__('Light')); ?></li>' +
            '<li class="sidebardark-action <?php echo e($settings['sidebar_mode'] == 'dark' ? 'active-mode' : ''); ?>"><?php echo e(__('Dark')); ?></li>' +
            '<li class="sidebargradient-action <?php echo e($settings['sidebar_mode'] == 'gradient' ? 'active-mode' : ''); ?>"><?php echo e(__('Gradient')); ?></li>' +
            '</ul> ' +
            '</div>' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title"><?php echo e(__('Layout Direction')); ?></h6> ' +
            '<ul class="customizeoption-list"> ' +
            '<li class="ltr-action <?php echo e($settings['layout_direction'] == 'ltrmode' ? 'active-mode' : ''); ?>"><?php echo e(__('LTR')); ?></li>' +
            '<li class="rtl-action <?php echo e($settings['layout_direction'] == 'rtlmode' ? 'active-mode' : ''); ?>"><?php echo e(__('RTL')); ?></li>' +
            '</ul> ' +
            '</div>' +

            <?php if(\Auth::user()->type == 'super admin'): ?>
                '<div class="cutomize-group"> ' +
                '<h6 class="customizer-title"><?php echo e(__('Registration Page')); ?></h6> ' +
                '<div> <label class="switch with-icon switch-primary"><input type="checkbox" name="register_page" id="register_page" <?php echo e($settings['register_page'] == 'on' ? 'checked' : ''); ?>>' +
                '<span class="switch-btn"></span></label></div>' +
                '</div>' +

                '<div class="cutomize-group"> ' +
                '<h6 class="customizer-title"><?php echo e(__('Landing Page')); ?></h6> ' +
                '<div> <label class="switch with-icon switch-primary"><input type="checkbox" name="landing_page" id="landing_page" <?php echo e($settings['landing_page'] == 'on' ? 'checked' : ''); ?>>' +
                '<span class="switch-btn"></span></label></div>' +
                '</div>' +
            <?php endif; ?>
            '<button type="submit" class="btn btn-primary mt-20"><?php echo e(__('Save')); ?></button>' +
            '</div>' +
            '</div></form>' +
            '');
    </script>
<?php endif; ?>
<!-- Datatable-->


<script>
    $(document).ready(function() {
        const table = $('table');

        if (!table.length) {
            console.warn("Table not found.");
        }

        let dataTable;

        dataTable = table.DataTable({
            // Your existing DataTables options
            dom: 'Bfrtip', // Add B for Buttons. This tells DataTables to render buttons.
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> <?php echo e(__('Export Excel')); ?>',
                    className: 'btn btn-success me-2', // Add Bootstrap classes for styling
                    exportOptions: {
                        // Specify columns to export. :visible ensures only visible columns are exported.
                        // :not(.no-export) excludes columns with the 'no-export' class.
                        columns: ':visible:not(.no-export)',
                        format: {
                            // Custom format function to get innerText, useful for badges/nested elements
                            body: function(data, row, column, node) {
                                return node.innerText.trim();
                            }
                        }
                    },
                    filename: 'all_invoices_' + new Date().toISOString().slice(0, 10).replace(/-/g,
                        '')
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="ti ti-file-text me-1"></i> <?php echo e(__('Export PDF')); ?>',
                    className: 'btn btn-danger me-2', // Add Bootstrap classes for styling
                    orientation: 'portrait', // 'portrait' or 'landscape'
                    pageSize: 'A4', // 'A4', 'LETTER', etc.
                    exportOptions: {
                        columns: ':visible:not(.no-export)',
                        format: {
                            body: function(data, row, column, node) {
                                // Strip HTML from content before PDF export
                                return node.innerText.trim();
                            }
                        }
                    },
                    // Custom PDF styling and title (this is where your "template" comes in)
                    title: 'Invoice List',
                    customize: function(doc) {
                        // Basic styles should usually be safe
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';

                        // ONLY access doc.content[0] if you are SURE it's the element you want to modify
                        // and that it exists. Often, the first content element is the table itself.
                        // If you are setting a custom title, you might insert it, not modify existing content[0]
                        // Example: Add a title at the beginning of content
                        // doc.content.splice(0, 0, { text: 'Your Custom Title Here', style: 'titleStyle' });

                        // For RTL layout in PDFMake, you also need to set the default text direction
                        doc.defaultStyle.direction = 'rtl'; // Add this for RTL support

                        // Example for Arabic text support in PDFMake
                        // PDFMake needs fonts embedded for non-Latin characters.
                        // This is server-side (Laravel-Dompdf does it too), but for client-side
                        // you need to configure vfs_fonts.js or customize pdfmake
                        // by providing a custom font object in the doc.
                        // If Arabic doesn't render, this is the most likely culprit.
                        doc.defaultStyle.font =
                            'Cairo'; // Example, requires Cairo font in vfs_fonts.js or custom fonts
                        doc.content[0].text = 'قائمة الفواتير'; // Example: Arabic title
                        doc.content[0].alignment = 'right'; // Align title to right for RTL

                        // Add a header
                        doc['header'] = (function() {
                            return {
                                columns: [{
                                        // You can add your company logo here. This path needs to be accessible from the client.
                                        // For local files, you might need base64 encoding or a publicly accessible URL.
                                        // Using an image path from server:
                                        // image: '<?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>', // THIS IS SERVER-SIDE. IF YOU CANNOT USE PHP, YOU MUST HOST THIS IMAGE PUBLICLY AND USE ITS URL.
                                        // width: 80,
                                        // alignment: 'left'
                                    },
                                    {
                                        text: '<?php echo e(__('Invoice List')); ?>', // Or a client-side string
                                        alignment: 'right',
                                        margin: [10, 10, 20, 0]
                                    }
                                ],
                                margin: [40, 20]
                            }
                        });

                        // Add a footer
                        doc['footer'] = (function(page, pages) {
                            return {
                                columns: [{
                                    text: 'Page ' + page.toString() + ' of ' +
                                        pages,
                                    alignment: 'center'
                                }],
                                margin: [40, 0]
                            }
                        });
                    },
                    filename: 'all_invoices_' + new Date().toISOString().slice(0, 10).replace(/-/g,
                        '')
                }
            ]
        });
    });
</script>


<script src="<?php echo e(asset('assets/js/vendors/select2/select2.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/vendors/sweetalert/sweetalert2.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/vendors/sweetalert/custom-sweetalert2.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/vendors/slider/slick-sldier/slick.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/vendors/slider/slick-sldier/slick-custom.js')); ?>"></script>



<!-- Custom script-->

<script src="<?php echo e(asset('assets/js/vendors/notify/bootstrap-notify.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/custom-script.js')); ?>"></script>
<?php echo $__env->yieldPushContent('script-page'); ?>

<script src="<?php echo e(asset('js/custom.js')); ?>"></script>
<?php if($statusMessage = Session::get('info')): ?>
    <script>
        toastrs('Info', '<?php echo $statusMessage; ?>', 'info')
    </script>
<?php endif; ?>
<?php if($statusMessage = Session::get('success')): ?>
    <script>
        toastrs('Success', '<?php echo $statusMessage; ?>', 'success')
    </script>
<?php endif; ?>
<?php if($statusMessage = Session::get('error')): ?>
    <script>
        toastrs('Error', '<?php echo $statusMessage; ?>', 'error')
    </script>
<?php endif; ?>
<?php /**PATH E:\JOWEB\property\resources\views/admin/footer.blade.php ENDPATH**/ ?>