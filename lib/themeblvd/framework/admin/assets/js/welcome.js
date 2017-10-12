/**
 * Welcome Message
 *
 * @package @@name-package
 * @subpackage @@name-framework
 * @license GPL-2.0+
 */

/**
 * Add welcome message modal window.
 *
 * @since @@name-framework 2.6.0
 *
 * @param {jQuery} $ jQuery object.
 */
( function( $ ) {

    $( document ).ready( function( $ ) {

        var $link = $( '#themeblvd-welcome-video-link' );

		/**
		 * Bind intro video modal to the video link within
		 * the welcome message.
		 *
		 * @since @@name-framework 2.6.0
		 */
        $link.themeblvd( 'modal', null, {
            title: $link.attr( 'title' ),
            build: true,
            vimeo: $link.data( 'video' ),
            size: 'medium',
            button: ''
        } );

    } );

} )( jQuery );
