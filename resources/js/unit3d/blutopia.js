$('img.img-tor-poster, i.img-tor-poster').popover({
    html: true,
    trigger: 'hover',
    placement: 'auto right',
    delay: { show: 150, hide: 100 },
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
    content: function() {
        let c = $(this).data('poster-mid');
        return '<img src="' + c + '" class="img-thumbnail torrent-poster-popup">';
    },
});
$(document).ready(function() {
    $(window).scroll(function() {
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
    $('#back-to-top').click(function() {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({ scrollTop: 0 }, 800);
        return false;
    });
    $('#back-to-down').click(function() {
        $('#back-to-down').tooltip('hide');
        $('body,html').animate({ scrollTop: $('body').height() }, 800);
        return false;
    });
    $('#back-to-top').tooltip('show');
    $('#back-to-down').tooltip('show');
});
$(document).ready(function() {
    $('#myCarousel').carousel({
        interval: 8000,
        pause: 'hover',
    });
});
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
