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
    });

    $('#back-to-top').click(function() {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({ scrollTop: 0 }, 800);
        return false;
    });

    $('#back-to-top').tooltip('show');
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
