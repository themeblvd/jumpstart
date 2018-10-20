/**
 * Admin Utilities: Tools
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Check if we're on a mobile device or not.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @return {bool} Whether it's a mobile device or not.
	 */
	admin.isMobile = function( $body ) {

		if ( ! $body ) {
			$body = $( 'body' );
		}

		if ( $body.hasClass( 'mobile' ) ) {
			return true;
		}

		var agentCheck = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent );

		if ( agentCheck ) {
			return true;
		}

		return false;

	};

} )( jQuery, window.themeblvd );
