<?php
/**
 * Helpers: Posts
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Get the ID of a post, from the slug.
 *
 * Note: $post_type isn't required, but it
 * will make retrieving the post ID much more
 * efficient.
 *
 * @since @@name-framework 2.0.0
 *
 * @uses $wpdb
 *
 * @param  string $slug      Post slug.
 * @param  string $post_type Optional. Post type.
 * @return int    $id        Post ID.
 */
function themeblvd_post_id_by_name( $slug, $post_type = null ) {

	global $wpdb;

	$null = null;

	$slug = sanitize_title( $slug );

	// Grab posts from DB (hopefully there's only one!)
	if ( $post_type ) {

		// More efficiant with post type.
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_name = %s AND (post_type = %s)",
				$slug,
				$post_type
			)
		);

	} else {

		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_name = %s",
				$slug
			)
		);

	}

	if ( empty( $posts ) ) {

		return $null;

	}

	/*
	 * Run through our results and return the ID of the
	 * first. Hopefully there was only one result, but
	 * if there was more than one, we'll just return a
	 * single ID.
	 */
	foreach ( $posts as $post ) {

		if ( $post->ID ) {

			return $post->ID;

		}
	}

	/*
	 * If for some odd reason, there was no ID in the
	 * returned post ID's, return nothing.
	 */
	return $null;

}

/**
 * Add to the post_class() function of WordPress.
 *
 * This function is filtered onto:
 * 1. `post_class` - 10
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $class Post class(es).
 * @return array $class Modified post class(es).
 */
function themeblvd_post_class( $class ) {

	if ( ! themeblvd_get_att( 'doing_second_loop' ) && is_single( themeblvd_config( 'id' ) ) ) {

		$class[] = 'single';

	}

	if ( is_page_template( 'template_naked.php' ) ) {

		$class[] = 'tb-naked-page';

	}

	return $class;

}

/**
 * Convert a publish date to the "time ago"
 * format.
 *
 * For example, a "time ago" formatted date
 * might look like "31 years ago" instead of
 * "September 17, 1986".
 *
 * @since @@name-framework 2.5.0
 *
 * @param  int    $post_id ID of post.
 * @return string $output  Time ago string.
 */
function themeblvd_get_time_ago( $post_id = 0 ) {

	if ( ! $post_id ) {

		$post_id = get_the_ID();

	}

	$date = get_post_time( 'G', true, $post_id );

	/**
	 * Filters the time ago verbiage.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array Text strings.
	 */
	$locals = apply_filters(
		'themeblvd_time_ago_locals', array(
			'year'    => __( 'year', '@@text-domain' ),
			'years'   => __( 'years', '@@text-domain' ),
			'month'   => __( 'month', '@@text-domain' ),
			'months'  => __( 'months', '@@text-domain' ),
			'week'    => __( 'week', '@@text-domain' ),
			'weeks'   => __( 'weeks', '@@text-domain' ),
			'day'     => __( 'day', '@@text-domain' ),
			'days'    => __( 'days', '@@text-domain' ),
			'hour'    => __( 'hour', '@@text-domain' ),
			'hours'   => __( 'hours', '@@text-domain' ),
			'minute'  => __( 'minute', '@@text-domain' ),
			'minutes' => __( 'minutes', '@@text-domain' ),
			'second'  => __( 'second', '@@text-domain' ),
			'seconds' => __( 'seconds', '@@text-domain' ),
			'ago'     => __( 'ago', '@@text-domain' ),
			'error'   => __( 'sometime', '@@text-domain' ),
		)
	);

	$chunks = array(
		array( 60 * 60 * 24 * 365, esc_html( $locals['year'] ), esc_html( $locals['years'] ) ),
		array( 60 * 60 * 24 * 30, esc_html( $locals['month'] ), esc_html( $locals['months'] ) ),
		array( 60 * 60 * 24 * 7, esc_html( $locals['week'] ), esc_html( $locals['weeks'] ) ),
		array( 60 * 60 * 24, esc_html( $locals['day'] ), esc_html( $locals['days'] ) ),
		array( 60 * 60, esc_html( $locals['hour'] ), esc_html( $locals['hours'] ) ),
		array( 60, esc_html( $locals['minute'] ), esc_html( $locals['minutes'] ) ),
		array( 1, esc_html( $locals['second'] ), esc_html( $locals['seconds'] ) ),
	);

	if ( ! is_numeric( $date ) ) {

		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );

		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );

		$date = gmmktime(
			(int) $time_chunks[1],
			(int) $time_chunks[2],
			(int) $time_chunks[3],
			(int) $date_chunks[1],
			(int) $date_chunks[2],
			(int) $date_chunks[0]
		);

	}

	$current_time = current_time( 'mysql', 1 );

	$newer_date = strtotime( $current_time );

	// Difference in seconds.
	$since = $newer_date - $date;

	/*
	 * Something went wrong with date calculation and
	 * we ended up with a negative date.
	 */
	if ( 0 > $since ) {

		return esc_html( $locals['error'] );

	}

	// Step one: the first chunk.
	$j = count( $chunks );

	for ( $i = 0; $i < $j; $i++ ) {

		$seconds = $chunks[ $i ][0];

		// Finding the biggest chunk (if the chunk fits, break).
		$count = floor( $since / $seconds );

		if ( 0 !== $count ) {

			break;

		}
	}

	// Set output var.
	$output = ( 1 == $count ) ? '1 ' . $chunks[ $i ][1] : $count . ' ' . $chunks[ $i ][2];

	if ( ! (int) trim( $output ) ) {

		$output = '0 ' . esc_html( $locals['seconds'] );

	}

	$output .= ' ' . esc_html( $locals['ago'] );

	return $output;

}

/**
 * Get all post display modes.
 *
 * @since @@name-framework 2.5.0
 *
 * @return array Post display modes.
 */
function themeblvd_get_modes() {

	/**
	 * Filters the list of framework post display
	 * modes.
	 *
	 * This primarily used for generating admin
	 * options, where a post display type needs
	 * to be selected.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array Post display modes.
	 */
	return apply_filters( 'themeblvd_modes', array(
		'blog'     => __( 'Blog', '@@text-domain' ),
		'list'     => __( 'List', '@@text-domain' ),
		'grid'     => __( 'Grid', '@@text-domain' ),
		'showcase' => __( 'Showcase', '@@text-domain' ),
	) );

}

/**
 * Get social media share sources.
 *
 * @since @@name-framework 2.5.0
 *
 * @return array $sources All sharing sources.
 */
function themeblvd_get_share_sources() {

	$sources = array(
		'digg'      => __( 'Digg', '@@text-domain' ),
		'email'     => __( 'Email', '@@text-domain' ),
		'facebook'  => __( 'Facebook', '@@text-domain' ),
		'google'    => __( 'Google+', '@@text-domain' ),
		'linkedin'  => __( 'Linkedin', '@@text-domain' ),
		'pinterest' => __( 'Pinterest', '@@text-domain' ),
		'reddit'    => __( 'Reddit', '@@text-domain' ),
		'tumblr'    => __( 'Tumblr', '@@text-domain' ),
		'twitter'   => __( 'Twitter', '@@text-domain' ),
	);

	/**
	 * Filters the available sources for setting
	 * up contact buttons to share a post URL.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $sources All sharing sources.
	 */
	return apply_filters( 'themeblvd_share_sources', $sources );

}

/**
 * Get social media share patterns.
 *
 * These get used when outputting the buttons
 * to share the URL for a post.
 *
 * @since @@name-framework 2.5.0
 *
 * @return array $sources Share button patterns.
 */
function themeblvd_get_share_patterns() {

	$patterns = array(
		'digg' => array(
			'pattern'     => 'http://digg.com/submit?url=[permalink]&title=[title]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'digg',
		),
		'email' => array(
			'pattern'     => 'mailto:?subject=[title]&amp;body=[permalink]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'envelope',
		),
		'facebook' => array(
			'pattern'     => 'http://www.facebook.com/sharer.php?u=[permalink]&amp;t=[title]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'facebook',
		),
		'google' => array(
			'pattern'     => 'https://plus.google.com/share?url=[permalink]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'google-plus',
		),
		'linkedin' => array(
			'pattern'     => 'http://linkedin.com/shareArticle?mini=true&amp;url=[permalink]&amp;title=[title]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'linkedin',
		),
		'pinterest' => array(
			'pattern'     => 'http://pinterest.com/pin/create/button/?url=[permalink]&amp;description=[title]&amp;media=[thumbnail]',
			'encode'      => true,
			'encode_urls' => true,
			'icon'        => 'pinterest-p',
		),
		'reddit' => array(
			'pattern'     => 'http://reddit.com/submit?url=[permalink]&amp;title=[title]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'reddit',
		),
		'tumblr' => array(
			'pattern'     => 'http://www.tumblr.com/share/link?url=[permalink]&amp;name=[title]&amp;description=[excerpt]',
			'encode'      => true,
			'encode_urls' => true,
			'icon'        => 'tumblr',
		),
		'twitter' => array(
			'pattern'     => 'http://twitter.com/home?status=[title] [shortlink]',
			'encode'      => true,
			'encode_urls' => false,
			'icon'        => 'twitter',
		),
	);

	/**
	 * Filters the share button patterns.
	 *
	 * Share buttons are outputted at the bottom of
	 * posts and allow website visitors to share the
	 * current URL of that post.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @return array $sources Share button patterns.
	 */
	return apply_filters( 'themeblvd_share_patterns', $patterns );

}
