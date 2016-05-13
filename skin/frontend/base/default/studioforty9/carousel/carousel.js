$j(document).on('ready', function() {
    var $window = $j(window);
    function onResizeComplete() {
        var $fotorama = $j('.fotorama');
        $fotorama.each(function() {
            var $instance = $j(this);
            $instance.find('.fotorama__img').each(function() {
                var $img = $j(this);
                var $html = $img.siblings('.fotorama__html').first();
                var $slide = $html.find('.carousel');
                var desktop = $slide.data('img');
                var tablet = $slide.data('img-tablet');
                var phone = $slide.data('img-phone');

                if ($window.width() <= 480) {
                    $img.attr('src', phone)
                }
                else if ($window.width() > 480 && $window.width() < 960) {
                    $img.attr('src', tablet);
                }
                else if ($window.width() >= 960) {
                    $img.attr('src', desktop);
                }

                $img.css({
                    'width': '100%',
                    left: 0
                });
            });
        });
    }

    $j('.fotorama').on('fotorama:load', function(e, fotorama) {
        $window.on('resize', onResizeComplete);
        onResizeComplete();
    }).fotorama();
});
