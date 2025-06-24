<!-- footer start-->
<footer class="codex-footer">
    <p>{{ __('Copyright') }} {{ date('Y') }} © {{ env('APP_NAME') }} {{ __('All rights reserved') }}.</p>
</footer>
<!-- footer end-->
<!-- back to top start //-->
<div class="scroll-top"><i class="fa fa-angle-double-up"></i></div>
<!-- back to top end //-->
<!-- main jquery-->

{{-- --}}

<!-- Custom script-->

<script src="{{ asset('assets/js/vendors/notify/bootstrap-notify.js') }}"></script>

<script src="{{ asset('assets/js/custom-script.js') }}"></script>

<script src="{{ asset('js/custom.js') }}"></script>


@if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'owner')
    <script>
        var public_path = '{{ asset('assets/css/') }}';
        $(".customizer-modal").append('' +
            '<form method="post" action="{{ route('theme.settings') }}">{{ csrf_field() }}<div class="customizer-layer"></div>' +
            '<div class="customizer-action bg-primary"><i data-feather="settings"></i>' +
            '</div><div class="theme-cutomizer"> ' +
            '<div class="customizer-header"> <h4>{{ __('Theme Setting') }}</h4> ' +
            '<div class="close-customizer"><i data-feather="x"></i></div>' +
            '</div>' +
            '<input type="hidden" name="theme_color" id="theme_color" value="{{ $settings['theme_color'] }}">' +
            '<input type="hidden" name="sidebar_mode" id="sidebar_mode" value="{{ $settings['sidebar_mode'] }}">' +
            '<input type="hidden" name="layout_direction" id="layout_direction" value="{{ $settings['layout_direction'] }}">' +
            '<input type="hidden" name="layout_mode" id="layout_mode" value="{{ $settings['layout_mode'] }}">' +
            '<input type="hidden" name="own_color" id="own_color" value="{{ $settings['own_color'] }}">' +
            '<input type="hidden" name="own_color_code" id="own_color_code" value="{{ $settings['own_color_code'] }}">' +
            '<input type="hidden" name="color_type" id="color_type" value="{{ $settings['color_type'] }}">' +
            '<div class="customizer-body"> ' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title">{{ __('Theme Color') }}</h6> ' +
            '<ul class="customizeoption-list themecolor-list" > ' +
            '<li class="color1 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color1' ? 'active-mode' : '' }}"></li>' +
            '<li class="color2 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color2' ? 'active-mode' : '' }}"></li>' +
            '<li class="color3 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color3' ? 'active-mode' : '' }}"></li>' +
            '<li class="color4 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color4' ? 'active-mode' : '' }}"></li>' +
            '<li class="color5 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color5' ? 'active-mode' : '' }}"></li>' +
            '<li class="color6 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color6' ? 'active-mode' : '' }}"></li>' +
            '<li class="color7 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color7' ? 'active-mode' : '' }}"></li>' +
            '<li class="color8 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color8' ? 'active-mode' : '' }}"></li>' +
            '<li class="color9 {{ $settings['color_type'] == 'default' && $settings['theme_color'] == 'color9' ? 'active-mode' : '' }}"></li>' +
            '</ul> ' +
            '<ul class="" > ' +
            '<li class="custom-color">{{ __('Choose Your Own Color') }} <input class="" value="{{ $settings['own_color_code'] }}" id="colorChange" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id7="transparentcolor" ></li>' +
            '</ul> ' +
            '</div>' +

            '<div class="cutomize-group "> ' +
            '<h6 class="customizer-title">{{ __('Layout mode') }}</h6> ' +
            '<ul class="customizeoption-list"> ' +
            '<li class="light-action {{ $settings['layout_mode'] == 'lightmode' ? 'active-mode' : '' }}">{{ __('Light') }}</li>' +
            '<li class="dark-action {{ $settings['layout_mode'] == 'darkmode' ? 'active-mode' : '' }}">{{ __('Dark') }}</li>' +
            '</ul> ' +
            '</div>' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title">{{ __('Sidebar Mode') }}</h6> ' +
            '<ul class="customizeoption-list sidebaroption-list"> ' +
            '<li class="sidebarlight-action {{ $settings['sidebar_mode'] == 'light' ? 'active-mode' : '' }}">{{ __('Light') }}</li>' +
            '<li class="sidebardark-action {{ $settings['sidebar_mode'] == 'dark' ? 'active-mode' : '' }}">{{ __('Dark') }}</li>' +
            '<li class="sidebargradient-action {{ $settings['sidebar_mode'] == 'gradient' ? 'active-mode' : '' }}">{{ __('Gradient') }}</li>' +
            '</ul> ' +
            '</div>' +
            '<div class="cutomize-group"> ' +
            '<h6 class="customizer-title">{{ __('Layout Direction') }}</h6> ' +
            '<ul class="customizeoption-list"> ' +
            '<li class="ltr-action {{ $settings['layout_direction'] == 'ltrmode' ? 'active-mode' : '' }}">{{ __('LTR') }}</li>' +
            '<li class="rtl-action {{ $settings['layout_direction'] == 'rtlmode' ? 'active-mode' : '' }}">{{ __('RTL') }}</li>' +
            '</ul> ' +
            '</div>' +

            @if (\Auth::user()->type == 'super admin')
                '<div class="cutomize-group"> ' +
                '<h6 class="customizer-title">{{ __('Registration Page') }}</h6> ' +
                '<div> <label class="switch with-icon switch-primary"><input type="checkbox" name="register_page" id="register_page" {{ $settings['register_page'] == 'on' ? 'checked' : '' }}>' +
                '<span class="switch-btn"></span></label></div>' +
                '</div>' +

                '<div class="cutomize-group"> ' +
                '<h6 class="customizer-title">{{ __('Landing Page') }}</h6> ' +
                '<div> <label class="switch with-icon switch-primary"><input type="checkbox" name="landing_page" id="landing_page" {{ $settings['landing_page'] == 'on' ? 'checked' : '' }}>' +
                '<span class="switch-btn"></span></label></div>' +
                '</div>' +
            @endif
            '<button type="submit" class="btn btn-primary mt-20">{{ __('Save') }}</button>' +
            '</div>' +
            '</div></form>' +
            '');
    </script>
@endif
<script>
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
                text: '<i class="ti ti-file-spreadsheet me-1"></i> {{ __('Export Excel') }}',
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
                text: '<i class="ti ti-file-text me-1"></i> {{ __('Export PDF') }}',
                className: 'btn btn-danger me-2', // Add Bootstrap classes for styling
                orientation: 'portrait', // 'portrait' or 'landscape'
                pageSize: 'A4', // 'A4', 'LETTER', etc.
                // exportOptions: {
                //     columns: ':visible:not(.no-export)',
                //     format: {
                //         body: function(data, row, column, node) {
                //             // Strip HTML from content before PDF export
                //             return node.innerText.trim();
                //         }
                //     }
                // },
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
                    doc.content[0].text = 'قائمة الفواتير'; // Example: Arabic title
                    doc.content[0].alignment = 'right'; // Align title to right for RTL

                    // Add a header
                    doc['header'] = (function() {
                        return {
                            columns: [{
                                    // You can add your company logo here. This path needs to be accessible from the client.
                                    // For local files, you might need base64 encoding or a publicly accessible URL.
                                    // Using an image path from server:
                                    // image: '{{ asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png') }}', // THIS IS SERVER-SIDE. IF YOU CANNOT USE PHP, YOU MUST HOST THIS IMAGE PUBLICLY AND USE ITS URL.
                                    // width: 80,
                                    // alignment: 'left'
                                },
                                {
                                    text: '{{ __('Invoice List') }}', // Or a client-side string
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
                filename: 'SH_GROUP {{ request()->url() }}' + new Date().toISOString().slice(0, 10)
                    .replace(/-/g,
                        '')
            }
        ]
    });


    const columnCount = dataTable.columns().header().length;

    function getDateColumnIndex() {
        for (let i = 0; i < columnCount; i++) {
            const header = $(dataTable.column(i).header()).text().trim();
            if (header === "تاريخ الإنشاء") {
                return i;
            }
        }
        return 0;
    }

    dataTable.order([getDateColumnIndex(), 'desc']).draw();

    // Bootstrap filter container
    const filterContainer = $('<div id="filters" class="d-flex flex-wrap gap-3 mb-3 w-full"></div>');
    $('#invoice-table_wrapper').before(filterContainer);

    for (let colIdx = 0; colIdx < columnCount - 1; colIdx++) {
        const columnHeader = $(dataTable.column(colIdx).header()).text().trim();
        const $wrapper = $('<div class="d-flex flex-column col me-3"></div>');
        const $label = $(`<label class="form-label fw-bold">${columnHeader}</label>`);

        if (columnHeader === "تاريخ الإنشاء") {
            const $input = $('<input type="date" class="form-control form-control-sm" />');

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
            const $select = $(`
                    <select class="form-select form-select-sm">
                        <option value="">All</option>
                    </select>
                `);

            const cellValues = new Set();
            dataTable.column(colIdx).data().each(function(value) {
                const text = $('<div>').html(value).text().trim();
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
</script>
<script src="{{ asset(path: 'assets/js/jquery.js') }}"></script>
<!-- Theme Customizer-->
<script src="{{ asset('assets/js/layout-storage.js') }}"></script>

{{-- <script src="{{ asset('assets/js/jquery.js') }}"></script> --}}
<script src="{{ asset('assets/js/customizer.js') }}"></script>
<!-- Feather icons js-->
<script src="{{ asset('assets/js/icons/feather-icon/feather.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap.bundle.js') }}"></script>
<!-- Scrollbar-->
<script src="{{ asset('assets/js/vendors/simplebar.js') }}"></script>
<!-- apex chart-->
<script src="{{ asset('assets/js/vendors/chart/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/vendors/select2/select2.js') }}"></script>

<script src="{{ asset('assets/js/vendors/sweetalert/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/vendors/sweetalert/custom-sweetalert2.js') }}"></script>

<script src="{{ asset('assets/js/vendors/slider/slick-sldier/slick.js') }}"></script>
<script src="{{ asset('assets/js/vendors/slider/slick-sldier/slick-custom.js') }}"></script>


@if ($statusMessage = Session::get('info'))
    <script>
        toastrs('Info', '{!! $statusMessage !!}', 'info')
    </script>
@endif
@if ($statusMessage = Session::get('success'))
    <script>
        toastrs('Success', '{!! $statusMessage !!}', 'success')
    </script>
@endif
@if ($statusMessage = Session::get('error'))
    <script>
        toastrs('Error', '{!! $statusMessage !!}', 'error')
    </script>
@endif
