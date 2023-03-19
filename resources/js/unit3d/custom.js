// Vibrant For Meta Backdrops
var metaElement = document.getElementById('meta-info');
var metaPoster = document.getElementById('meta-poster');
if (metaElement && metaPoster) {
    if (!metaPoster.src.includes('via.placeholder')) {
        Vibrant.from(metaPoster.src)
            .getPalette()
            .then(function (palette) {
                var rgb = palette.DarkMuted.getRgb();
                rgb.push(0.75);
                var rgba = 'rgba(' + rgb.join(',') + ')';
                $meta = $(metaElement);
                $meta.find('.movie-overlay').css('background-color', rgba);
                $meta.find('.button-overlay').css('opacity', 0);
                $meta
                    .find('.vibrant-overlay')
                    .css({ opacity: 1, background: 'linear-gradient(to bottom, ' + rgba + ', transparent)' });
            });
    }
}

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
