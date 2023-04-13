// Scroll To Top/Bottom
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
        if ($(this).scrollTop() + $(window).height() < $(document).height() - 50) {
            $('#back-to-down').fadeIn();
        } else {
            $('#back-to-down').fadeOut();
        }
    });
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({ scrollTop: 0 }, 800);
        return false;
    });
    $('#back-to-down').click(function () {
        $('#back-to-down').tooltip('hide');
        $('body,html').animate({ scrollTop: $('body').height() }, 800);
        return false;
    });
    $('#back-to-top').tooltip('show');
    $('#back-to-down').tooltip('show');
});

// Bootstrap Tooltips
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
