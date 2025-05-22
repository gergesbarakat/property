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
<script src="<?php echo e(asset('assets/js/customizer.js')); ?>"></script>
<!-- Feather icons js-->
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather.js')); ?>"></script>
<!-- Bootstrap js-->
<script src="<?php echo e(asset('assets/js/bootstrap.bundle.js')); ?>"></script>
<!-- Scrollbar-->
<script src="<?php echo e(asset('assets/js/vendors/simplebar.js')); ?>"></script>
<!-- apex chart-->
<script src="<?php echo e(asset('assets/js/vendors/chart/apexcharts.js')); ?>"></script>


<script src="<?php echo e(asset('assets/js/vendors/select2/select2.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/vendors/sweetalert/sweetalert2.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/vendors/sweetalert/custom-sweetalert2.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/vendors/slider/slick-sldier/slick.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/vendors/slider/slick-sldier/slick-custom.js')); ?>"></script>
<!-- Datatable-->


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




<script>
    $('select').addClass(
        ' form-select form-select-sm border border-gray-300 text-body cursor-pointer shadow-sm '
    );
    <?php if(!request()->routeIs('dashboard')): ?>
        $(document).ready(function() {
            const table = $('#invoice-table');

            if (!table.length) {
                console.warn("Table not found.");
                return;
            }

            const dataTable = table.DataTable({
                scrollX: true
            });

            const columnCount = dataTable.columns().header().length;

            function getDateColumnIndex() {
                for (let i = 0; i < columnCount; i++) {
                    const header = $(dataTable.column(i).header()).text().trim();
                    if (header === "تاريخ الإنشاء" || header === "Date Created") {
                        return i;
                    }
                }
                return 0;
            }

            // Sort table by date column descending
            dataTable.order([getDateColumnIndex(), 'desc']).draw();

            // Create Bootstrap filter container
            const filterContainer = $('<div id="filters" class="row g-3 mb-4"></div>');
            $('#invoice-table_wrapper').before(filterContainer);

            for (let colIdx = 0; colIdx < columnCount - 1; colIdx++) {
                const columnHeader = $(dataTable.column(colIdx).header()).text().trim();
                const $wrapper = $('<div class="col-md-3"></div>');
                const $label = $(`<label class="form-label fw-semibold">${columnHeader}</label>`);

                if (columnHeader === "تاريخ الإنشاء" || columnHeader === "Date Created" || columnHeader ===
                    "Date Updated") {
                    // 📅 Date input
                    const $input = $('<input type="date" class="form-control form-control-sm" />');

                    // Custom filter logic for date
                    $.fn.dataTable.ext.search.push(function(settings, data) {
                        const inputDate = $input.val();
                        if (!inputDate) return true;

                        const cellDate = data[colIdx];
                        if (!cellDate) return false;

                        try {
                            const inputISO = new Date(inputDate).toISOString().split('T')[0];
                            const cellISO = new Date(cellDate).toISOString().split('T')[0];
                            return inputISO === cellISO;
                        } catch (e) {
                            return false;
                        }
                    });

                    $input.on('change', function() {
                        dataTable.draw();
                    });

                    $wrapper.append($label).append($input);
                } else {
                    // 🔽 Default select dropdown
                    const $select = $(`<select class="form-select form-select-sm">
                <option value="">All</option>
            </select>`);

                    const cellValues = new Set();
                    dataTable.column(colIdx).data().each(function(value) {
                        const text = $('<div>').html(value).text().trim(); // decode HTML
                        if (text) cellValues.add(text);
                    });

                    Array.from(cellValues).sort().forEach(value => {
                        $select.append(`<option value="${value}">${value}</option>`);
                    });

                    $select.on('change', function() {
                        const val = $.fn.dataTable.util.escapeRegex($(this).val());
                        dataTable.column(colIdx).search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                    $wrapper.append($label).append($select);
                }

                filterContainer.append($wrapper);
            }
        });
    <?php endif; ?>

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('input[type="password"]').forEach(function(input) {
            // Create eye icon wrapper
            const wrapper = document.createElement('div');
            wrapper.classList.add('relative');

            // Clone the input and insert into wrapper
            const clonedInput = input.cloneNode(true);
            input.replaceWith(wrapper);
            wrapper.appendChild(clonedInput);

            // Create the toggle icon
            const toggleIcon = document.createElement('span');
            toggleIcon.innerHTML = '👁️'; // You can use a better SVG/icon if needed
            toggleIcon.classList.add(
                'absolute', 'right-2', 'top-1/2', '-translate-y-1/2',
                'cursor-pointer'
            );
            wrapper.appendChild(toggleIcon);

            // Toggle logic
            toggleIcon.addEventListener('click', () => {
                if (clonedInput.type === 'password') {
                    clonedInput.type = 'text';
                    toggleIcon.innerHTML =
                        '🙈'; // icon changes when visible
                } else {
                    clonedInput.type = 'password';
                    toggleIcon.innerHTML = '👁️';
                }
            });
        });
    });
</script>
<script>
    function exportToExcel() {
        const table = document.getElementById("invoice-table"); // adjust ID
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "Invoice"
        });
        XLSX.writeFile(workbook, "invoice.xlsx");
    }
</script>
<script>
    async function exportToPDF(htmlString) {
        const {
            jsPDF
        } = window.jspdf;
        const pdf = new jsPDF();

        // Create a temporary container
        const tempDiv = document.createElement('div');

        tempDiv.style.position = 'fixed'; // keep it offscreen
        tempDiv.style.left = '-9999px';
        tempDiv.style.padding = '10px';
        tempDiv.style.display = 'flex';
        tempDiv.style.alignItems = 'left';
        tempDiv.style.justifyContent = 'center';
        tempDiv.style.flexDirection = 'column';
        tempDiv.style.width = '900px';

        tempDiv.innerHTML = htmlString;

        // Append to body so styles apply (optional but recommended)
        document.body.appendChild(tempDiv);

        // Select the element to convert (e.g. the whole invoice)
        const invoiceElement = tempDiv.querySelector('html') || tempDiv;

        // Use html2canvas on this element
        await html2canvas(invoiceElement).then(canvas => {
            const imgData = canvas.toDataURL("image/png");
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth() - 10;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save("invoice.pdf");
        });

        // Clean up
        document.body.removeChild(tempDiv);
    }

    function setEqualColumnWidths(tableHtml) {
        const tempContainer = document.createElement('div');
        tempContainer.innerHTML = tableHtml;

        const table = tempContainer.querySelector('table');
        const headerRow = table.querySelector('tr');
        const colCount = headerRow ? headerRow.children.length : 0;

        if (colCount > 0) {
            const colWidth = 800 / colCount;
            table.style.width = '100%';
            table.style.tableLayout = 'fixed';

            table.querySelectorAll('th, td').forEach(cell => {
                cell.style.width = `${colWidth}px`;
                cell.style.wordWrap = 'break-word';
                cell.style.whiteSpace = 'normal';
                cell.style.padding = '6px';
                cell.style.fontSize = '12px';
                cell.style.border = '1px solid #ccc';
            });
        }

        return table.outerHTML;
    }
</script>
<script>
    document.getElementById('generate-pdf').addEventListener('click', function() {
        const table = document.querySelector('#invoice-table'); // Your table selector
        if (!table) return alert("Table not found");

        // Clone and clean the table
        const clonedTable = table.cloneNode(true);

        // Remove DataTable-related classes and styles
        clonedTable.querySelectorAll('*').forEach(el => {
            el.removeAttribute('style');
            el.removeAttribute('class');
        });

        // Remove last column (assumed to be actions or unwanted)
        clonedTable.querySelectorAll('tr').forEach(row => {
            const cells = row.querySelectorAll('th, td');
            if (cells.length > 1) {
                row.removeChild(cells[cells.length - 1]);
            }
        });

        // Calculate equal column width
        const firstRow = clonedTable.querySelector('tr');
        const colCount = firstRow ? firstRow.children.length : 1;
        const colWidth = 800 / colCount;

        // Optional: inject width style to each cell
        clonedTable.querySelectorAll('th, td').forEach(cell => {
            cell.style.width = `${colWidth}px`;
            cell.style.wordWrap = 'break-word';
            cell.style.whiteSpace = 'normal';
        });

        const cleanedTableHtml = clonedTable.outerHTML;

        // Prepare data to send
        const formData = new FormData();
        formData.append('table_html', cleanedTableHtml);
        formData.append('name',
            'Test Customer');
        formData.append('adress', 'Hurghada, Egypt');
        formData.append('invoiceid',
            'INV-123');
        formData.append('invoicedate', new Date().toISOString().slice(0, 10));
        formData.append('total',
            '150.00');

        formData.append('col_width', colWidth);

        fetch('<?php echo e(url('/invoice/generate')); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData,
            })
            .then(response => response.text()) // expect HTML response
            .then(html => {
                // At this stage you have the full invoice.blade.php rendered HTML as a string in 'html'
                console.log(html);
                exportToPDF(html)
            })
            .catch(console.error);
    });
</script>
<?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/admin/footer.blade.php ENDPATH**/ ?>