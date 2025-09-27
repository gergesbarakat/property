<!-- footer start-->
<footer class="codex-footer">
    <p>{{ __('Copyright') }} {{ date('Y') }} © {{ env('APP_NAME') }} {{ __('All rights reserved') }}.</p>
</footer>



<!-- footer end-->

<!-- back to top start //-->

<div class="scroll-top"><i class="fa fa-angle-double-up"></i></div>
<!-- back to top end //-->
<!-- main jquery-->
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/html2canvas.js') }}"></script>

<!-- Theme Customizer-->
<script src="{{ asset('assets/js/layout-storage.js') }}"></script>

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
<!-- Datatable-->
<script src="{{ asset('assets/js/vendors/datatable/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/dataTables.buttons.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/buttons.print.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/jszip.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/pdfmake.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/vendors/datatable/buttons.html5.js') }}"></script>

<!-- Custom script-->

<script src="{{ asset('assets/js/vendors/notify/bootstrap-notify.js') }}"></script>
<script src="{{ asset('assets/js/customizer.js') }}"></script>

<script src="{{ asset('assets/js/custom-script.js') }}"></script>
@stack('script-page')

<script src="{{ asset('js/custom.js') }}"></script>


@if (!request()->routeIs('invoice.show'))
    <script>
        const table = $('table');

        if (!table.length) {
            console.warn("Table not found.");
        }

        let dataTable;

        dataTable = table.DataTable({
            "responsive": true,
            "scrollX": true,
            "width": "auto",
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,

            "columnDefs": [{
                "targets": -1, // Target the last column
                "orderable": false // Disable sorting on the 'Actions' column
            }],
            "dom": 'Bfrtip',
            "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> {{ __('Export to Excel') }}',
                    className: 'btn btn-success',
                    titleAttr: 'Export to Excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Actions)
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf"></i> {{ __('Export to PDF') }}',
                    className: 'btn btn-danger',
                    titleAttr: 'Export to PDF',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __('Print') }}',
                    className: 'btn btn-primary',
                    titleAttr: 'Print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
        });

        // Add margin between filters and DataTable controls (buttons/search)
        $('#filters').addClass('mb-10');
        $('.dt-buttons').addClass('mb-10');
        $('.dataTables_filter').addClass('mb-11');





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
        const filterContainer = $('<div id="filters" class="d-flex flex-wrap gap-3 mb-3"></div>');
        $('#invoice-table_wrapper').before(filterContainer);

        for (let colIdx = 0; colIdx < columnCount - 1; colIdx++) {
            const columnHeader = $(dataTable.column(colIdx).header()).text().trim();
            const $wrapper = $('<div class="d-flex flex-column me-3"></div>');
            const $label = $(`<label class="form-label fw-bold">${columnHeader}</label>`);
            if (columnHeader.toLowerCase().includes("updated") || columnHeader.toLowerCase().includes("created") ||
                columnHeader.toLowerCase().includes("date")) {
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
@endif
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
