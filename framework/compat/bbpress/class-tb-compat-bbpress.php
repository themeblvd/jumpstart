<?php
/**
 * Add extended bbPress compatibility
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Compat_bbPress {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
	 * Breadcrumb trail
	 *
	 * @since 2.5.0
	 */
	private $crumbs = array();

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Compat_bbPress A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Custom stylesheet
		add_action( 'wp_enqueue_scripts', array($this, 'assets') );
		add_filter( 'themeblvd_framework_stylesheets', array($this, 'add_style') );

		// Remove redundant descriptions
		add_filter( 'bbp_get_single_forum_description', array($this, 'remove_desc'), 10, 2 );
		add_filter( 'bbp_get_single_topic_description', array($this, 'remove_desc'), 10, 2 );

		// Search results
		add_filter( 'bbp_register_forum_post_type', array($this, 'register_post_types') );
        add_filter( 'bbp_register_topic_post_type', array($this, 'register_post_types') );
        add_filter( 'bbp_register_reply_post_type', array($this, 'register_post_types') );
        add_filter( 'bbp_allow_search', array($this, 'remove_search') );
        add_filter( 'themeblvd_format_icon', array($this, 'format_icon'), 10, 4 );

        // Breadcrumbs
        add_filter( 'bbp_get_breadcrumb', array($this, 'remove_breadcrumb'), 10, 3 );
		add_filter( 'themeblvd_pre_breadcrumb_parts', array($this, 'add_breadcrumb'), 10, 2 );

		// Pagination
		add_filter( 'bbp_get_topic_pagination_links', array($this, 'pagination') );
		add_filter( 'bbp_get_forum_pagination_links', array($this, 'pagination') );
		add_filter( 'bbp_get_search_pagination_links', array($this, 'pagination') );

		// Widgets
		add_filter( 'bbp_get_logout_link', array($this, 'widget_login') );

		// Strip randomly added characters for lack of CSS styling (like non-breakable spaces)
		add_filter( 'bbp_get_topic_revision_log', array($this, 'strip') );
		add_filter( 'bbp_get_reply_revision_log', array($this, 'strip') );
		add_filter( 'bbp_get_topic_author_link', array($this, 'strip') );
		add_filter( 'bbp_get_reply_author_link', array($this, 'strip') );

        // Add wrapping classes for bbPress pages
        add_filter( 'post_class', array($this, 'post_class'), 10, 3 );

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$api = Theme_Blvd_Stylesheets_API::get_instance();

		$deps = $api->get_framework_deps();
		$deps[] = 'bbp-default';

		wp_enqueue_style( 'themeblvd_bbp', TB_FRAMEWORK_URI.'/compat/bbpress/bbpress.css', $deps, TB_FRAMEWORK_VERSION );
	}

	/**
	 * Add our stylesheet to framework $deps. This will make
	 * sure our bbpress.css file comes between framework
	 * styles and child theme's style.css
	 *
	 * @since 2.5.0
	 */
	public function add_style( $deps ) {
		$deps[] = 'bbpress';
		return $deps;
	}

	/**
	 * Remove redundant descriptions
	 *
	 * @since 2.5.0
	 */
	public function remove_desc( $retstr, $args ) {
		return '';
	}

	/**
	 * Filter when bbPress registers all of its custom
	 * post types. Here we can make sure their post types
	 * are included in our global search results.
	 *
	 * @since 2.5.0
	 */
	public function register_post_types( $type ) {
		$type['exclude_from_search'] = false;
		return $type;
	}

	/**
	 * Remove searchbox from forums; the theme has
	 * forums, topics, and replies integrated into
	 * default site search results.
	 *
	 * @since 2.5.0
	 */
	public function remove_search() {
		return false;
	}

	/**
	 * In search results, adjust the icon returned for
	 * forums, topics, and replies.
	 *
	 * @since 2.5.0
	 */
	public function format_icon( $icon, $format, $force, $post_type ) {

		$post_id = get_the_ID();

		if ( $force && $post_type ) {

			switch ( $post_type ) {

				// Forusm - open or closed
				case 'forum' :
					if ( bbp_get_forum_status( $post_id ) == 'closed' ) {
						$icon = 'folder';
					} else {
						$icon = 'folder-open';
					}
					break;

				// Topics - closed, sticky, or open (default)
				case 'topic' :
					if ( bbp_get_topic_status( $post_id ) == 'closed' ) {
						$icon = 'check';
					} else if ( bbp_is_topic_sticky( $post_id ) ) {
						$icon = 'bullhorn';
					} else {
						$icon = 'comment';
					}
					break;

				// Replies
				case 'reply' :
					$icon = 'comments';

			}
		}

		return $icon;
	}

	/**
	 * Remove default breadcrumbs
	 *
	 * @since 2.5.0
	 */
	public function remove_breadcrumb( $trail, $crumbs, $r ) {
		if ( ! $this->crumbs ) {
			$this->crumbs = $crumbs;
		}
		return '';
	}

	/**
	 * Add breadcrumbs from bbPress to ours'
	 *
	 * @since 2.5.0
	 * @see themeblvd_get_breadcrumb_parts()
	 */
	public function add_breadcrumb( $parts, $atts ) {

		$bbp = bbp_get_breadcrumb();
		$parts = array();

		if ( $this->crumbs ) {
			foreach ( $this->crumbs as $crumb ) {

				// We'll add the Home button in our breadcrumbs
				if ( strpos($crumb, 'bbp-breadcrumb-home') ) {
					continue;
				}

				// Crumb class
				if ( preg_match("/<a\s[^>]*class=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $crumb, $matches) ) {
					$class = $matches[2];
				} else if ( preg_match("/<span\s[^>]*class=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/span>/siU", $crumb, $matches) ) {
					$class = $matches[2];
				} else {
					$class = '';
				}

				$class = str_replace('bbp-breadcrumb-', '', $class);

				if ( strpos($crumb, '<span') !== false ) {

					$link = '';
					$text = str_replace( array('<span class="bbp-breadcrumb-current">', '</span>'), '', $crumb);

				} else {

					// Crumb url
					if ( preg_match("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $crumb, $matches) ) {
						$link = $matches[2];
					} else {
						$link = '';
					}

					// Text
					$start = strpos($crumb, '>');
					$end = strpos($crumb, '</a>');
					$text = substr( $crumb, ($start+1), ($end-$start-1) );

				}

				$parts[] = array(
					'link'	=> $link,
					'text'	=> $text,
					'type'	=> $class
				);

			}
		}

		return $parts;
	}

	/**
	 * Pagination links
	 *
	 * @since 2.5.0
	 */
	public function pagination( $input ) {

		if ( ! $input ) {
			return '';
		}

		$output  = '<div class="pagination-wrap">';
		$output .= '<div class="pagination">';
		$output .= '<div class="btn-group clearfix">';

		$current  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
		$current .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
		$current .= $_SERVER["REQUEST_URI"];

		$input = str_replace("<span class='page-numbers current'>", '<a class="btn btn-default active" href="'.$current.'">', $input);
		$input = str_replace('</span>', '</a>', $input);

		$input = str_replace('page-numbers', 'btn btn-default', $input);

		$input = str_replace('&larr;', '&lsaquo;', $input);
		$input = str_replace('&rarr;', '&rsaquo;', $input);

		$output .= $input;

		$output .= '</div><!-- .btn-group (end) -->';
		$output .= '</div><!-- .pagination (end) -->';
		$output .= '</div><!-- .pagination-wrap (end) -->';

		return $output;
	}

	/**
	 * Filter logout link used in login widget, to
	 * display some more info in the widget.
	 *
	 * @since 2.5.0
	 */
	public function widget_login( $logout ) {

		$user_id = bbp_get_current_user_id();
		$user = get_userdata( $user_id );

		$html .= '<ul>';
		$html .= sprintf( '<li class="username">%s</li>', esc_html($user->display_name) );
		$html .= sprintf( '<li class="my-account"><a href="%s">%s</a></li>', esc_url(bbp_get_user_profile_url($user_id)), themeblvd_get_local('my_account') );
		$html .= sprintf( '<li class="edit-profile"><a href="%s">%s</a></li>', esc_url(bbp_get_user_profile_edit_url($user_id)), themeblvd_get_local('edit_profile') );
		$html .= sprintf( '<li class="logout">%s</li>', $logout );
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Strip randomly added characters for lack of
	 * CSS styling (like non-breakable spaces)
	 *
	 * @since 2.5.0
	 */
	public function strip( $html ) {
		return str_replace('&nbsp;', '', $html);
	}

	/**
	 * Add to article wrap for bbPress pages
	 *
	 * @since 2.5.0
	 * @see post_class()
	 */
	public function post_class( $classes, $class, $post_id ) {

		if ( is_bbpress() && ! $post_id ) {
			$classes[] = 'bbp-page';
			$classes[] = 'page';
		}

		return $classes;
	}

}