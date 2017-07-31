(function($) {

    'use strict';

    $(document).ready(function($){

        if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {

            var $link = $('#themeblvd-welcome-video-link');

            $link.ThemeBlvdModal({
                title: $link.attr('title'),
                build: true,
                vimeo: $link.data('video'),
                size: 'medium',
                button: ''
            });
        }

    });

})(jQuery);
