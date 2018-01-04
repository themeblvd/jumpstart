<?php
/**
 * Helpers: Comments
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Get the arguments passed to wp_list_comments()
 * in the theme's comments.php template.
 *
 * @see wp_list_comments()
 *
 * @since @@name-framework 2.2.0
 *
 * @return array $args Arguments prepared for wp_list_comments().
 */
function themeblvd_get_comment_list_args() {

	/**
	 * Filters the arguments passed to wp_list_comments()
	 * in the theme's comments.php template.
	 *
	 * @see wp_list_comments()
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array $args Arguments prepared for wp_list_comments().
	 */
	return apply_filters( 'themeblvd_comment_list', array(
		'avatar_size'       => 60,
		'style'             => 'ul',
		'type'              => 'all',
		'reply_text'        => themeblvd_get_local( 'reply' ),
		'login_text'        => themeblvd_get_local( 'login_text' ),
		'callback'          => null,
		'reverse_top_level' => null,
		'reverse_children'  => false,
	) );

}

/**
 * Get the arguments passed to comment_form()
 * in the theme's comments.php template.
 *
 * @see comment_form()
 *
 * @since @@name-framework 2.2.0
 *
 * @return array $args Arguments prepared for comment_form().
 */
function themeblvd_get_comment_form_args() {

	/**
	 * Filters the arguments passed to comment_form()
	 * in the theme's comments.php template.
	 *
	 * @see comment_form()
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array $args Arguments prepared for comment_form().
	 */
	return apply_filters( 'themeblvd_comment_form', array(
		'comment_field'     => '<p class="comment-form-comment"><label for="comment">' . themeblvd_get_local( 'comment' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'title_reply'       => themeblvd_get_local( 'title_reply' ),
		'title_reply_to'    => themeblvd_get_local( 'title_reply_to' ),
		'cancel_reply_link' => themeblvd_get_local( 'cancel_reply_link' ),
		'label_submit'      => themeblvd_get_local( 'label_submit' ),
	) );

}

/**
 * Add filtered, frontend text strings to replace
 * default WordPress comment labels.
 *
 * This function is filtered onto:
 * 1. `comment_form_default_fields` - 10
 *
 * @param  array $fields Comment form fields.
 * @return array $fields Modified comment form fields.
 */
function themeblvd_comment_form_fields( $fields ) {

	$fields['author'] = str_replace(
		'Name',
		themeblvd_get_local( 'name' ),
		$fields['author']
	);

	$fields['email'] = str_replace(
		'Email',
		themeblvd_get_local( 'email' ),
		$fields['email']
	);

	$fields['url'] = str_replace(
		'Website ',
		themeblvd_get_local( 'website' ),
		$fields['url']
	);

	return $fields;

}

/**
 * Determine whether comments should have any presence
 * for current post. Must be within the loop.
 *
 * At first glance, you're probably wondering why this
 * would exist when you the WP user could just close
 * the comments. When the user closes the comments,
 * comments will still be present and in place of the
 * comment form, it will say that the comments are closed.
 *
 * However, in addition to that, this framework allows
 * the user to completely hide the comments presence.
 * So, this extends further up than simply having the
 * comments for a post closed.
 *
 * @since @@name-framework 2.2.0
 *
 * @return bool $show Whether to show comments.
 */
function themeblvd_show_comments() {

	global $post;

	$show = true;

	/*
	 * If comments presence has been hidden for all single
	 * posts, this inevitably extends to the comments presence
	 * for all posts.
	 *
	 * This will extend to pages, as well, if comments are
	 * enabled for pages.
	 */
	if ( 'hide' === themeblvd_get_option( 'single_comments', null, 'show' ) ) {

		$show = false;

	}

	/*
	 * If comments presence has been hidden for this single
	 * post, this extends to the comments presence everywhere
	 * for this post.
	 */
	if ( 'hide' === get_post_meta( $post->ID, '_tb_comments', true ) ) {

		$show = false;

	} elseif ( 'show' === get_post_meta( $post->ID, '_tb_comments', true ) ) {

		$show = true;

	}

	/*
	 * If the current post's type doesn't support comments,
	 * comments presence should be hidden.
	 */
	if ( $show && ! post_type_supports( get_post_type(), 'comments' ) ) {

		$show = false;

	}

	/*
	 * If comments are closed AND no comments exist, then it
	 * doesn't make sense to have any comments presence.
	 */
	if ( $show && ! comments_open() && ! have_comments() ) {

		$show = false;

	}

	/**
	 * Filters whether to include comments presence.
	 *
	 * @see themeblvd_show_comments()
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @return bool $show Whether to show comments.
	 */
	return apply_filters( 'themeblvd_show_comments', $show );

}

/**
 * Get comment section title, used in the comments.php
 * template.
 *
 * In order to maintain our frontend filterable text
 * strings, this function has to avoid using _n(),
 * which might looks seemingly weird, at first glance.
 *
 * @since @@name-framework 2.5.2
 *
 * @return string $output Comments title.
 */
function themeblvd_get_comments_title() {

	$output = '';

	$num = intval( get_comments_number() );

	if ( 0 === $num ) {

		$output = themeblvd_get_local( 'no_comments' );

	} elseif ( 1 === $num ) {

		$output = sprintf(
			themeblvd_get_local( 'comments_title_single' ),
			number_format_i18n( get_comments_number() ),
			'<span>' . esc_html( get_the_title() ) . '</span>'
		);

	} elseif ( $num >= 2 ) {

		$output = sprintf(
			themeblvd_get_local( 'comments_title_multiple' ),
			number_format_i18n( get_comments_number() ),
			'<span>' . esc_html( get_the_title() ) . '</span>'
		);

	}

	/**
	 * Filters the comment section title, used in the
	 * comments.php template.
	 *
	 * @since @@name-framework 2.5.2
	 *
	 * @param string $output Comments title.
	 * @param string $num    Number of comments.
	 */
	return apply_filters( 'themeblvd_comments_title', $output, $num );

}

/**
 * Display comment section title, used in the
 * comments.php template.
 *
 * @since @@name-framework 2.5.2
 */
function themeblvd_comments_title() {

	echo themeblvd_get_comments_title();

}
