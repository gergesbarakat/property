// Customizer

//*** Light & Dark action  ***//
$('.action-dark').click(function () {
    $(this).toggleClass('action-light');
    $('.icon-dark').toggle('');
    $('.icon-light').toggle('');
    $('body').toggleClass('darkmode');
});

//*** Customizer Action ***//
$('.customizer-action').click(function () {
    $('.theme-cutomizer , .customizer-layer').toggleClass('active');
});

$('.customizer-header').click(function () {
    $('.theme-cutomizer , .customizer-layer').toggleClass('active');
});

$('.customizer-layer').click(function () {
    $(this).removeClass('active');
    $('.theme-cutomizer').removeClass('active');
});

//*** Dark Mode ***//
$('.dark-action').click(function () {
    $('body').addClass('darkmode');
    $('#layout_mode').val('darkmode');
});

$('.light-action').click(function () {
    $('body').removeClass('darkmode');
    $('#layout_mode').val('lightmode');
});

$('.customizeoption-list li').click(function () {
    $(this).addClass('active-mode')
    $(this).siblings().removeClass('active-mode');
});

//*** Direction Mode ***//
$('.ltr-action').click(function () {
    $('body').removeClass('rtlmode');
    $('#layout_direction').val('ltrmode');
});
$('.rtl-action').click(function () {
    $('body').addClass('rtlmode');
    $('#layout_direction').val('rtlmode');
});

//*** Sidebar Mode ***//
$('.sidebardark-action').click(function () {
    $('.codex-sidebar').addClass('sidebar-dark');
    $('.codex-sidebar').removeClass('sidebar-gradient');
    $('#sidebar_mode').val('dark');
});
$('.sidebarlight-action').click(function () {
    $('.codex-sidebar').removeClass('sidebar-dark');
    $('.codex-sidebar').removeClass('sidebar-gradient');
    $('#sidebar_mode').val('light');
});
$('.sidebargradient-action').click(function () {
    $('.codex-sidebar').addClass('sidebar-gradient');
    $('#sidebar_mode').val('gradient');
});

//** Theme color mode  ***//
$('.themecolor-list').on('click', '.color1', function () {
    $("#customstyle").attr("href", "YOUR_PUBLIC_PATH/color1.css"); // Note: Corrected path
    $('#theme_color').val('color1');
    $('#color_type').val('default');
    return false;
});
// ... (repeat for color2, color3, etc., making sure to correct the path)

// Function to set the color variable and save to local storage
function setColorAndSave(color) {
    const rgbColor = hexToRgb(color);
    $('#own_color').val('--primary-rgb: ' + rgbColor);
    $('#color_type').val('custom');
    document.documentElement.style.setProperty('--primary-rgb', rgbColor);
    // localStorage.setItem('primaryColor', color);
}

// Function to convert hex color to RGB
function hexToRgb(hex) {
    hex = hex.replace('#', '');
    const bigint = parseInt(hex, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `${r},${g},${b}`;
}

// Get the color picker input element
const colorPicker = document.getElementById('colorChange');

// ✅ THE FIX: Check if the colorPicker element exists before using it.
if (colorPicker) {
    const savedColor = localStorage.getItem('primaryColor');
    if (savedColor) {
        setColorAndSave(savedColor);
        colorPicker.value = savedColor;
    }

    // Listen for changes in the color picker
    colorPicker.addEventListener('input', function (event) {
        const selectedColor = event.target.value;
        $('#own_color_code').val(selectedColor);
        setColorAndSave(selectedColor);
    });
}


const resetButtons = document.querySelectorAll('.color1, .color2, .color3, .color4, .color5, .color6, .color7, .color8, .color9');

// ✅ THE FIX: Check if resetButtons were found before looping.
if (resetButtons) {
    resetButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            document.documentElement.removeAttribute('style');
            // localStorage.removeItem('--primary-rgb');
        });
    });
}
