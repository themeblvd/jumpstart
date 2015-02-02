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

		// Custom styles. We're not creating a new theme or
		// completely replacing default stylesheet, but just overriding
		// select styles of the current bbPress default theme.
		add_action( 'wp_enqueue_scripts', array($this, 'assets') );
		add_filter( 'themeblvd_framework_stylesheets', array($this, 'add_style') );
		add_filter( 'body_class', array($this, 'body_class') );
		add_filter( 'post_class', array($this, 'post_class'), 10, 3 );

		// Sidebar layouts
		add_filter( 'themeblvd_sidebar_layout', array($this, 'sidebar_layout') );

		// Remove redundant descriptions
		add_filter( 'bbp_get_single_forum_description', array($this, 'remove_desc'), 10, 2 );
		add_filter( 'bbp_get_single_topic_description', array($this, 'remove_desc'), 10, 2 );

		// Search results
		add_filter( 'bbp_register_forum_post_type', array($this, 'register_pt') );
		add_filter( 'bbp_register_topic_post_type', array($this, 'register_pt') );
		add_filter( 'bbp_register_reply_post_type', array($this, 'register_pt') );
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

		// Remove inline breaks (separators) from author details
		add_filter( 'bbp_after_get_topic_author_link_parse_args', array($this, 'author') );
		add_filter( 'bbp_after_get_reply_author_link_parse_args', array($this, 'author') );

		// Output wrapped subscribe/fav links for more conistent styling
		add_action( 'bbp_template_before_forums_index', array($this, 'forum_subscribe') );
		add_action( 'bbp_template_before_single_forum', array($this, 'forum_subscribe') );

		// Lead topic
		add_action( 'wp', array($this, 'lead_topic') );

		// Add user's website URL to public profile
		if ( apply_filters('themeblvd_bbp_do_website', true) ) {
			add_filter( 'bbp_get_displayed_user_field', array($this, 'user_website'), 10, 2 );
		}

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

		wp_enqueue_style( 'themeblvd-bbp', TB_FRAMEWORK_URI.'/compat/bbpress/bbpress.min.css', $deps, TB_FRAMEWORK_VERSION );
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
	 * Any classes needed for <body> tag
	 *
	 * @since 2.5.0
	 * @see body_class()
	 */
	public function body_class( $classes ) {

		if ( bbp_is_topic() && bbp_show_lead_topic() ) {
			$classes[] = 'bbp-show-lead-topic';
		}

		return $classes;
	}

	/**
	 * Add to article wrap for bbPress pages
	 *
	 * @since 2.5.0
	 * @see post_class()
	 */
	public function post_class( $classes, $class, $post_id ) {

		if ( is_bbpress() ) {

			if ( get_post_type($post_id) == bbp_get_topic_post_type() || get_post_type($post_id) == bbp_get_reply_post_type() ) {
				$classes[] = 'clearfix';
			}

			if ( in_array('top', $class) ) {
				$classes[] = 'bbp-page';
				$classes[] = 'page';
			}
		}

		return $classes;
	}

	/**
	 * Apply sidebar layouts from theme options
	 *
	 * @since 2.5.0
	 */
	public function sidebar_layout( $layout ) {

		if ( is_bbpress() ) {

			if ( bbp_is_single_topic() || bbp_is_single_reply() || bbp_is_topic_edit() || bbp_is_topic_merge() || bbp_is_topic_split() || bbp_is_reply_edit() || bbp_is_reply_move() ) {
				$layout = themeblvd_get_option('bbp_topic_sidebar_layout');
			} else if ( bbp_is_single_user() ) {
				$layout = themeblvd_get_option('bbp_user_sidebar_layout');
			} else {
				$layout = themeblvd_get_option('bbp_sidebar_layout');
			}

			if ( ! $layout || $layout == 'default' ) {
				$layout = themeblvd_get_option('sidebar_layout');
			}

		}

		return $layout;
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
	public function register_pt( $type ) {
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

		if ( is_bbpress() && $this->crumbs ) {

			$parts = array();

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

		} else if ( bbp_is_single_user() ) {

			$page = bbp_get_page_by_path( bbp_get_root_slug() );

			if ( ! empty( $page ) ) {
				$url = get_permalink( $page->ID );
			} else {
				$url = get_post_type_archive_link( bbp_get_forum_post_type() );
			}

			$parts = array(
				array(
					'link'	=> $url,
					'text'	=> bbp_get_forum_archive_title(),
					'type'	=> 'page'
				),
				array(
					'link'	=> '',
					'text'	=> get_the_title(),
					'type'	=> 'page'
				)
			);

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

		$html  = '<ul>';
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
	 * Wrap forum subscribe link. Why not filter the
	 * subscribe link itself? In case any plugins are
	 * filtering additional links with the forum subscribe
	 * link, we want to style those links, as well.
	 *
	 * @since 2.5.0
	 */
	public function forum_subscribe() {
		if ( bbp_get_user_subscribe_link() ) {
			echo '<div class="tb-bbp-buttons forum-subscribe">';
			bbp_forum_subscription_link();
			echo '</div><!-- .tb-bbp-buttons (end) -->';
		}
	}

	/**
	 * Remove any default separators from author details.
	 *
	 * @since 2.5.0
	 */
	public function author( $args ) {
		$args['sep'] = '';
		return $args;
	}

	/**
	 * Setup lead topic. Hooked to "wp" action so we make use
	 * of themeblvd_get_option()
	 *
	 * @since 2.5.0
	 */
	public function lead_topic() {
		if ( apply_filters('themeblvd_bbp_show_lead_topic', themeblvd_get_option('bbp_lead_topic') ) ) {
			add_filter( 'bbp_show_lead_topic', '__return_true' );
			add_filter( 'get_post_metadata', array($this, 'hide_lead_title'), 10, 4 );
			add_action( 'bbp_template_before_lead_topic', array($this, 'lead_before') );
			add_action( 'bbp_template_after_lead_topic', array($this, 'lead_after') );
		} else {
			add_action( 'bbp_template_before_replies_loop', array($this, 'topic_header') );
		}
	}

	/**
	 * On a single topic, hide the title. We're doing this
	 * so we can display the title below with the lead topic.
	 *
	 * @since 2.5.0
	 * @see get_metadata()
	 */
	public function hide_lead_title( $value, $object_id, $meta_key, $single ) {

		if ( $meta_key == '_tb_title' && bbp_is_single_topic() && $single ) {
			$value = 'hide';
		}

		return $value;
	}

	/**
	 * Before lead topic. Output opening DIV and new title section.
	 *
	 * @since 2.5.0
	 */
	public function lead_before() {
		?>
		<div class="tb-lead-topic">
			<div class="wrap">
				<header class="lead-topic-header clearfix">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
		<?php
	}

	/**
	 * After lead topic. Output closing DIV.
	 *
	 * @since 2.5.0
	 */
	public function lead_after() {
		$tags = array(
			'before' 	=> '<div class="tb-tags bbp-tags tags"><i class="fa fa-tags"></i>',
			'after' 	=> '</div><!-- .tb-tags (end) -->'
		);
		?>
			</div><!-- .wrap (end) -->

			<div class="clearfix">

				<?php bbp_topic_tag_list( bbp_get_topic_id(), $tags ); ?>

				<?php if ( is_user_logged_in() ) : ?>
					<div class="tb-bbp-buttons topic-subscribe clearfix">
						<?php bbp_topic_subscription_link(); ?>
						<?php bbp_topic_favorite_link(); ?>
					</div>
				<?php endif; ?>

			</div>

		</div><!-- .tb-lead-topic" (end) -->
		<?php
	}

	/**
	 * Output subscribe links for a topic, when not using the
	 * lead topic display.
	 *
	 * @since 2.5.0
	 */
	public function topic_header() {

		$tags = array(
			'before' 	=> '<div class="tb-tags bbp-tags tags"><i class="fa fa-tags"></i>',
			'after' 	=> '</div><!-- .tb-tags (end) -->'
		);

		if ( is_user_logged_in() || $tag_list = bbp_get_topic_tag_list(bbp_get_topic_id(), $tags) ) {

			echo '<div class="topic-header clearfix">';

			echo $tag_list;

			if ( is_user_logged_in() ) {
				echo '<div class="tb-bbp-buttons topic-subscribe clearfix">';
				bbp_topic_subscription_link();
				bbp_user_favorites_link();
				echo '</div><!-- .tb-bbp-buttons (end) -->';
			}

			echo '</div>';

		}
	}

	/**
	 * Add user's website URL to output with profile.
	 *
	 * @since 2.5.0
	 */
	public function user_website( $value, $field ) {

		if ( $field == 'description' && ! bbp_is_single_user_edit() && $website = bbp_get_displayed_user_field('user_url') ) {
			$value .= '</p><p class="bbp-user-forum-role">';
			$value .= themeblvd_get_local('website').': ';
			$value .= sprintf('<a href="%s" target="_blank">%s</a>', $website, str_replace(array('http://', 'https://'), '', $website));
		}

		return $value;
	}

}
