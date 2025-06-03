$(document).ready(function() {
    "use strict";
    select2();

});


$(document).on('click', '.customModal', function() {
    "use strict";
    var modalTitle = $(this).data('title');
    var modalUrl = $(this).data('url');
    var modalSize = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    $("#customModal .modal-title").html(modalTitle);
    $("#customModal .modal-dialog").addClass('modal-' + modalSize);
    $.ajax({
        url: modalUrl,
        success: function(result) {
            $('#customModal .body').html(result);
            $("#customModal").modal('show');
            select2();
        },
        error: function(result) {}
    });

});

// basic message
$(document).on('click', '.confirm_dialog', function(e) {
    "use strict";
    var dialogForm = $(this).closest("form");
    Swal.fire({
        title: 'Are you sure you want to delete this record ?',
        text: "This record can not be restore after delete. Do you want to confirm?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((data) => {
        if (data.isConfirmed) {
            dialogForm.submit();
        }
    })
});


$(document).on('click', '.fc-day-grid-event', function(e) {
    "use strict";
    e.preventDefault();
    var event = $(this);
    var modalTitle = $(this).find('.fc-content .fc-title').html();
    var modalSize = 'md';
    var modalUrl = $(this).attr('href');
    $("#customModal .modal-title").html(modalTitle);
    $("#customModal .modal-dialog").addClass('modal-' + modalSize);
    $.ajax({
        url: modalUrl,
        success: function(result) {
            $('#customModal .modal-body').html(result);
            $("#customModal").modal('show');
        },
        error: function(result) {}
    });
});


function toastrs(title, message, status) {
    "use strict";
    if (status == 'success') {
        var msg_status = 'primary';
    } else {
        var msg_status = 'danger';
    }
    $.notify({
        title: '',

        message: message,
        icon: '',
        url: '',
        target: '_blank'
    }, {
        element: 'body',
        type: msg_status,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 3300,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutRight'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class',
    });
}


function select2() {
    "use strict";
    $('.basic-select').select2();
    $('.hidesearch').select2({
        minimumResultsForSearch: -1
    });
}


const table = $('#invoice-table');

if (!table.length) {
    console.warn("Table not found.");
}

let dataTable;

dataTable = table.DataTable({
    "scrollX": true,
    // stateSave: true,
    // dom: 'Bfrtip',
    // buttons: [
    //     'print', 'excel', 'pdf', 'csv', 'copy',
    // ]
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
const filterContainer = $('<div id="filters" class="d-flex flex-wrap gap-3 mb-3"></div>');
$('#invoice-table_wrapper').before(filterContainer);

for (let colIdx = 0; colIdx < columnCount - 1; colIdx++) {
    const columnHeader = $(dataTable.column(colIdx).header()).text().trim();
    const $wrapper = $('<div class="d-flex flex-column me-3"></div>');
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


// Password toggle with Bootstrap
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('input[type="password"]').forEach(function(input) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('position-relative');

        const clonedInput = input.cloneNode(true);
        input.replaceWith(wrapper);
        wrapper.appendChild(clonedInput);

        const toggleIcon = document.createElement('span');
        toggleIcon.innerHTML = '👁️';
        toggleIcon.classList.add(
            'position-absolute', 'top-50', 'end-0', 'translate-middle-y', 'pe-2',
            'cursor-pointer'
        );
        wrapper.appendChild(toggleIcon);

        toggleIcon.addEventListener('click', () => {
            if (clonedInput.type === 'password') {
                clonedInput.type = 'text';
                toggleIcon.innerHTML = '🙈';
            } else {
                clonedInput.type = 'password';
                toggleIcon.innerHTML = '👁️';
            }
        });
    });
});