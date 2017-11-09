<?php
/**
 * Plugin Compatibility: bbPress
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Add extended bbPress compatibility.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since Theme_Blvd 2.5.0
 */
class Theme_Blvd_Compat_BBPress {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	private static $instance = null;

	/**
	 * Holds bbPress breadcrumbs, so that it can
	 * be merged with framework breadcrumbs.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	private $crumbs = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @return Theme_Blvd_Compat_bbPress A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function __construct() {

		/*
		 * Add custom styles.
		 *
		 * We're not creating a new theme or completely replacing
		 * default stylesheet, but just overriding select styles
		 * of the current bbPress default theme.
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

		add_filter( 'themeblvd_framework_stylesheets', array( $this, 'add_style' ) );

		add_filter( 'body_class', array( $this, 'body_class' ) );

		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );

		// Apply sidebar layouts from theme options.
		add_filter( 'themeblvd_sidebar_layout', array( $this, 'sidebar_layout' ) );

		// Remove redundant descriptions.
		add_filter( 'bbp_get_single_forum_description', array( $this, 'remove_desc' ), 10, 2 );

		add_filter( 'bbp_get_single_topic_description', array( $this, 'remove_desc' ), 10, 2 );

		/*
		 * Setup search functionality.
		 *
		 * bbPress keeps its forum searching separated from the
		 * main WordPress site search. So here we're merging it
		 * back with the default WordPress search, which the
		 * theme framework handles and visually separates by post
		 * type.
		 */
		add_filter( 'bbp_register_forum_post_type', array( $this, 'register_post_type' ) );

		add_filter( 'bbp_register_topic_post_type', array( $this, 'register_post_type' ) );

		add_filter( 'bbp_register_reply_post_type', array( $this, 'register_post_type' ) );

		add_filter( 'bbp_allow_search', array( $this, 'remove_search' ) );

		add_filter( 'themeblvd_format_icon', array( $this, 'format_icon' ), 10, 4 );

		/*
		 * Handles breadcrumbs.
		 *
		 * We first remove bbPress breadcrumbs from displaying,
		 * but then store it in the object so we can reference it.
		 *
		 * Next, we use that stored bbPress breadcrumb trail to
		 * build a new breadcrumbs trail that can be filtered into
		 * the theme framework's default breadcrumbs system.
		 */
		add_filter( 'bbp_get_breadcrumb', array( $this, 'remove_breadcrumb' ), 10, 3 );

		add_filter( 'themeblvd_pre_breadcrumb_parts', array( $this, 'add_breadcrumb' ), 10, 2 );

		// Adjust pagination to be styled to match the theme framework.
		add_filter( 'bbp_get_topic_pagination_links', array( $this, 'pagination' ) );

		add_filter( 'bbp_get_forum_pagination_links', array( $this, 'pagination' ) );

		add_filter( 'bbp_get_search_pagination_links', array( $this, 'pagination' ) );

		// Add more information to the bbPress login widget, by adding to logout link.
		add_filter( 'bbp_get_logout_link', array( $this, 'logout_link' ) );

		// Remove unnecessary unbreakable spaces.
		add_filter( 'bbp_get_topic_revision_log', array( $this, 'strip' ) );

		add_filter( 'bbp_get_reply_revision_log', array( $this, 'strip' ) );

		add_filter( 'bbp_get_topic_author_link', array( $this, 'strip' ) );

		add_filter( 'bbp_get_reply_author_link', array( $this, 'strip' ) );

		// Remove inline breaks (separators) from author details.
		add_filter( 'bbp_after_get_topic_author_link_parse_args', array( $this, 'author' ) );

		add_filter( 'bbp_after_get_reply_author_link_parse_args', array( $this, 'author' ) );

		// Output wrapped subscribe and favorite links, for more conistent styling.
		add_action( 'bbp_template_before_forums_index', array( $this, 'forum_subscribe' ) );

		add_action( 'bbp_template_before_single_forum', array( $this, 'forum_subscribe' ) );

		/*
		 * Setup "lead topic" style.
		 *
		 * @TODO Make 'lead topic' the default and remove theme option.
		 */
		add_action( 'wp', array( $this, 'lead_topic' ) );

		// Add user's website URL to public profile.
		if ( apply_filters( 'themeblvd_bbp_do_website', true ) ) {

			add_filter( 'bbp_get_displayed_user_field', array( $this, 'user_website' ), 10, 2 );

		}

	}

	/**
	 * Add overriding CSS for bbPress pages.
	 *
	 * This method is filtered onto `wp_enqueue_scripts`.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function assets() {

		$handler = Theme_Blvd_Stylesheet_Handler::get_instance();

		$deps = $handler->get_framework_deps();

		$deps[] = 'bbp-default';

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'themeblvd-bbpress',
			esc_url( TB_FRAMEWORK_URI . "/compat/assets/css/bbpress{$suffix}.css" ),
			$deps,
			TB_FRAMEWORK_VERSION
		);

	}

	/**
	 * Add our stylesheet to framework $deps.
	 *
	 * This will help to ensure that the theme's
	 * bbpress.css file gets loaded in the
	 * correct place.
	 *
	 * This method is filtered onto
	 * `themeblvd_framework_stylesheets`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  array $deps Stylesheets the theme framework depends on.
	 * @return array $deps Modified stylesheets the theme framework depends on.
	 */
	public function add_style( $deps ) {

		$deps[] = 'bbpress';

		return $deps;

	}

	/**
	 * Add CSS classes needed to the <body> tag, for
	 * custom styling from our bbpress.css.
	 *
	 * This method is filtered onto `body_class`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see body_class()
	 *
	 * @param  array $class Current body classes from WordPress.
	 * @return array $class Modified body classes.
	 */
	public function body_class( $class ) {

		if ( bbp_is_topic() && bbp_show_lead_topic() ) {

			$class[] = 'bbp-show-lead-topic';

		}

		if ( bbp_is_user_home() ) {

			$class[] = 'bbp-user-home';

		}

		return $class;

	}

	/**
	 * Add CSS classes to article wrap for bbPress
	 * pages.
	 *
	 * This method is filtered onto `post_class`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see post_class()
	 *
	 * @param  array $classes An array of post classes.
	 * @param  array $class   An array of additional classes added to the post.
	 * @param  int   $post_id ID of current post.
	 * @return array $classes Modified array of post classes.
	 */
	public function post_class( $classes, $class, $post_id ) {

		if ( is_bbpress() ) {

			if ( get_post_type( $post_id ) == bbp_get_topic_post_type() || get_post_type( $post_id ) == bbp_get_reply_post_type() ) {

				$classes[] = 'clearfix';

			}

			if ( is_array( $class ) && in_array( 'top', $class ) ) {

				$classes[] = 'bbp-page';

				$classes[] = 'page';

			}

			if ( is_array( $class ) && in_array( 'top', $class ) && themeblvd_get_option( 'bbp_naked_page' ) && ( bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_single_forum() || bbp_is_single_topic() ) ) {

				$classes[] = 'tb-naked-page';

			}
		}

		return $classes;

	}

	/**
	 * Apply custom sidebar layouts for bbPress pages,
	 * depending on selections from the theme options
	 * page.
	 *
	 * This method is filetered onto `themeblvd_sidebar_layout`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $layout Sidebar layout for current page.
	 * @return string $layout Modified sidebar layout for current page.
	 */
	public function sidebar_layout( $layout ) {

		if ( is_bbpress() ) {

			if ( bbp_is_single_topic() || bbp_is_single_reply() || bbp_is_topic_edit() || bbp_is_topic_merge() || bbp_is_topic_split() || bbp_is_reply_edit() || bbp_is_reply_move() ) {

				$layout = themeblvd_get_option( 'bbp_topic_sidebar_layout' );

			} elseif ( bbp_is_single_user() ) {

				$layout = themeblvd_get_option( 'bbp_user_sidebar_layout' );

			} else {

				$layout = themeblvd_get_option( 'bbp_sidebar_layout' );

			}

			if ( ! $layout || 'default' === $layout ) {

				$layout = themeblvd_get_option( 'sidebar_layout' );

			}
		}

		return $layout;

	}

	/**
	 * Remove redundant descriptions.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_get_single_forum_description`
	 * 2. `bbp_get_single_topic_description`
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $output Description to output.
	 * @param  array  $args   Arguments originally passed for description.
	 * @return string         Modified description to output.
	 */
	public function remove_desc( $output, $args ) {

		return '';

	}

	/**
	 * Include bbPress post types in site search
	 * results.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_register_forum_post_type`
	 * 2. `bbp_register_topic_post_type`
	 * 3. `bbp_register_reply_post_type`
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  array $args Arguments used to register post type.
	 * @return array $args Modified arguments used to register post type.
	 */
	public function register_post_type( $args ) {

		$args['exclude_from_search'] = false;

		return $args;

	}

	/**
	 * Disable forum-wide searching.
	 *
	 * Because the theme framework integrates bbPress
	 * post types into the standard, site-wide search,
	 * we no longer need the bbPress-specific search
	 * box.
	 *
	 * This method is filtered onto `bbp_allow_search`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @return bool Whether to allow bbPress forum-wide searching.
	 */
	public function remove_search() {

		return false;

	}

	/**
	 * Add icons to site-wide search results for
	 * bbPress post types.
	 *
	 * Now that we've adjusted post types, forums,
	 * topics, and replies, to show in site-wide search,
	 * we can specify which icons in the theme should
	 * be used to visually represent them.
	 *
	 * This method is filtered onto `themeblvd_format_icon`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $icon        Icon name.
	 * @param string $format      Post format of current post.
	 * @param bool   $force       Whether to force an icon to still show when no post format.
	 * @param string $post_format Post type of current post.
	 */
	public function format_icon( $icon, $format, $force, $post_type ) {

		$post_id = get_the_ID();

		if ( $force && $post_type ) {

			switch ( $post_type ) {

				// Apply icons for `forum` post type.
				case 'forum':
					if ( bbp_get_forum_status( $post_id ) == 'closed' ) {

						$icon = 'folder';

					} else {

						$icon = 'folder-open';

					}

					break;

				// Apply icons for `topic` post type.
				case 'topic':
					if ( 'closed' === bbp_get_topic_status( $post_id ) ) {

						$icon = 'check';

					} elseif ( bbp_is_topic_sticky( $post_id ) ) {

						$icon = 'bullhorn';

					} else {

						$icon = 'comment';

					}

					break;

				// Apply icon for `reply` post type.
				case 'reply':
					$icon = 'comments';

			}
		}

		return $icon;

	}

	/**
	 * Remove default breadcrumbs.
	 *
	 * Here, we're removing the default bbPress
	 * breadcrumbs but then storing it in the object,
	 * so it can be referenced when building breadcrumbs
	 * into theme framework's system.
	 *
	 * This method is filtered onto `bbp_get_breadcrumb`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $trail  Final HTML output of default bbPress breadcrumbs.
	 * @param array  $crumbs Links used for the default bbPress breadcrumbs.
	 * @param array  $args   Original arguments used for the bbPress breadcrumbs.
	 */
	public function remove_breadcrumb( $trail, $crumbs, $args ) {

		if ( ! $this->crumbs ) {

			$this->crumbs = $crumbs;

		}

		return '';

	}

	/**
	 * Add bbPress breadcrumbs to theme framework's
	 * breadcrumbs.
	 *
	 * This method is filtered onto
	 * `themeblvd_pre_breadcrumb_parts`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see themeblvd_get_breadcrumb_parts()
	 *
	 * @param  array $parts Breadcrumb parts to display trail.
	 * @param  array $args {
	 *     Breadcrumb arguments.
	 *
	 *     @type string $delimiter HTML between breadcrumb pieces.
	 *     @type string $home      Home link text.
	 *     @type string $home_link Home link URL.
	 *     @type string $before    HTML before current breadcrumb item.
	 *     @type string $after     HTML after current breadcrumb item.
	 * }
	 * @return array $parts Modified breadcrumb parts to display trail.
	 */
	public function add_breadcrumb( $parts, $args ) {

		if ( is_bbpress() && $this->crumbs ) {

			$parts = array();

			foreach ( $this->crumbs as $crumb ) {

				// Strip home link from bbPress's breadcrumbs; we'll add our own.
				if ( strpos( $crumb, 'bbp-breadcrumb-home' ) ) {

					continue;

				}

				if ( preg_match( "/<a\s[^>]*class=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $crumb, $matches ) ) {

					$class = $matches[2];

				} elseif ( preg_match( "/<span\s[^>]*class=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/span>/siU", $crumb, $matches ) ) {

					$class = $matches[2];

				} else {

					$class = '';

				}

				$class = str_replace( 'bbp-breadcrumb-', '', $class );

				if ( false !== strpos( $crumb, '<span' ) ) {

					$link = '';

					$text = str_replace(
						array( '<span class="bbp-breadcrumb-current">', '</span>' ),
						'',
						$crumb
					);

				} else {

					if ( preg_match( "/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $crumb, $matches ) ) {

						$link = $matches[2];

					} else {

						$link = '';

					}

					$start = strpos( $crumb, '>' );

					$end = strpos( $crumb, '</a>' );

					$text = substr( $crumb, ( $start + 1 ), ( $end - $start - 1 ) );

				}

				$parts[] = array(
					'link' => $link,
					'text' => $text,
					'type' => $class,
				);

			}
		} elseif ( bbp_is_single_user() ) {

			$page = bbp_get_page_by_path( bbp_get_root_slug() );

			if ( ! empty( $page ) ) {

				$url = get_permalink( $page->ID );

			} else {

				$url = get_post_type_archive_link( bbp_get_forum_post_type() );

			}

			$parts = array(
				array(
					'link' => $url,
					'text' => bbp_get_forum_archive_title(),
					'type' => 'page',
				),
				array(
					'link' => '',
					'text' => get_the_title(),
					'type' => 'page',
				),
			);

		}

		return $parts;

	}

	/**
	 * Modify default bbPress pagination markup to
	 * work better with theme framework styling.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_get_topic_pagination_links`
	 * 2. `bbp_get_forum_pagination_links`
	 * 3. `bbp_get_search_pagination_links`
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $input  HTML for bbPress pagination.
	 * @return string $output Modified HTML for bbPress pagination.
	 */
	public function pagination( $input ) {

		if ( ! $input ) {
			return '';
		}

		// First, adjust the the inner HTML bits.
		$input = str_replace( "<span class='page-numbers", "<span class='btn btn-default", $input );

		$input = str_replace( "<a class='page-numbers", "<a class='btn btn-default", $input );

		$input = str_replace( '<a class="page-numbers', '<a class="btn btn-default', $input );

		$input = str_replace( "<a class='next page-numbers", "<a class='next btn btn-default", $input );

		$input = str_replace( '<a class="next page-numbers', '<a class="next btn btn-default', $input );

		$input = str_replace( "<a class='prev page-numbers", "<a class='prev btn btn-default", $input );

		$input = str_replace( '<a class="prev page-numbers', '<a class="prev btn btn-default', $input );

		$input = str_replace( '&larr;', '&lsaquo;', $input );

		$input = str_replace( '&rarr;', '&rsaquo;', $input );

		// Then, wrap modified $input, to built $output.
		$output  = '<div class="pagination-wrap">';

		$output .= '<div class="pagination">';

		$output .= '<div class="btn-group clearfix">';

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
	 * This method is filtered onto `bbp_get_logout_link`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $input  HTML for bbPress logout link.
	 * @return string $output Modified HTML for bbPress logout link.
	 */
	public function logout_link( $input ) {

		$user_id = bbp_get_current_user_id();

		$user = get_userdata( $user_id );

		$output  = '<ul>';

		$output .= sprintf(
			'<li class="username">%s</li>',
			esc_html( $user->display_name )
		);

		$output .= sprintf(
			'<li class="my-account"><a href="%s">%s</a></li>',
			esc_url( bbp_get_user_profile_url( $user_id ) ),
			themeblvd_get_local( 'my_account' )
		);

		$output .= sprintf(
			'<li class="edit-profile"><a href="%s">%s</a></li>',
			esc_url( bbp_get_user_profile_edit_url( $user_id ) ),
			themeblvd_get_local( 'edit_profile' )
		);

		$output .= sprintf( '<li class="logout">%s</li>', $input );

		$output .= '</ul>';

		return $output;

	}

	/**
	 * Remove unnecessary unbreakable spaces.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_get_topic_revision_log`
	 * 2. `bbp_get_reply_revision_log`
	 * 3. `bbp_get_topic_author_link`
	 * 4. `bbp_get_reply_author_link`
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $input HTML from bbPress.
	 * @return string        Modifeid HTML sent back to bbPress.
	 */
	public function strip( $input ) {

		return str_replace( '&nbsp;', '', $input );

	}

	/**
	 * Wrap forum subscribe link.
	 *
	 * Why not filter the subscribe link it self? -
	 * In case any plugins are filtering additional links
	 * with the forum subscribe link, we want to style
	 * those links, as well.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_template_before_forums_index`
	 * 2. `bbp_template_before_single_forum`
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function forum_subscribe() {

		if ( bbp_get_user_subscribe_link() ) {

			echo '<div class="tb-bbp-buttons forum-subscribe">';

			bbp_forum_subscription_link();

			echo '</div><!-- .tb-bbp-buttons (end) -->';

		}

	}

	/**
	 * Remove any default separators from author
	 * details.
	 *
	 * This method is filtered onto:
	 * 1. `bbp_after_get_topic_author_link_parse_args`
	 * 2. `bbp_after_get_reply_author_link_parse_args`
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function author( $args ) {

		$args['sep'] = '';

		return $args;

	}

	/**
	 * Setup lead topic. Hooked to "wp" action so we
	 * make use of themeblvd_get_option().
	 *
	 * @since Theme_Blvd 2.5.0
	 * @TODO Will get removed from fixing issue #326; code will get moved to constructor.
	 */
	public function lead_topic() {

		/**
		 * Filters whether lead topic is applied to
		 * bbPress.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param bool Whether bbPress's lead-topic is applied.
		 */
		if ( apply_filters( 'themeblvd_bbp_show_lead_topic', themeblvd_get_option( 'bbp_lead_topic' ) ) ) {

			add_filter( 'bbp_show_lead_topic', '__return_true' );

			add_filter( 'get_post_metadata', array( $this, 'hide_lead_title' ), 10, 4 );

			add_action( 'bbp_template_before_lead_topic', array( $this, 'lead_before' ) );

			add_action( 'bbp_template_after_lead_topic', array( $this, 'lead_after' ) );

		} else {

			add_action( 'bbp_template_before_replies_loop', array( $this, 'topic_header' ) );

		}

	}

	/**
	 * On a single topic, hide the title.
	 *
	 * We're doing this so we can display the title
	 * below with the lead topic.
	 *
	 * This method is filtered onto `get_post_metadata`.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see get_metadata()
	 *
	 * @param  null|array|string $value     Meta data value to filter.
	 * @param  int               $object_id Object ID.
	 * @param  string            $meta_key  Meta key.
	 * @param  bool              $single    Whether to return only the first value of the specified $meta_key.
	 * @return null|array|string $value     Possibly modified original $value.
	 */
	public function hide_lead_title( $value, $object_id, $meta_key, $single ) {

		if ( '_tb_title' === $meta_key && bbp_is_single_topic() && $single ) {

			$value = 'hide';

		}

		return $value;

	}

	/**
	 * Before lead topic. Output opening DIV and
	 * new title section.
	 *
	 * @since Theme_Blvd 2.5.0
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
	 * @since Theme_Blvd 2.5.0
	 */
	public function lead_after() {

		$tags = array(
			'before' => '<div class="tb-tags bbp-tags tags"><i class="fa fa-tags"></i>',
			'after'  => '</div><!-- .tb-tags (end) -->',
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

		</div><!-- .tb-lead-topic (end) -->
		<?php
	}

	/**
	 * Output subscribe links for a topic, when not using the
	 * lead topic display.
	 *
	 * @TODO Will get removed from fixing issue #326.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function topic_header() {

		$tags = array(
			'before'    => '<div class="tb-tags bbp-tags tags"><i class="fa fa-tags"></i>',
			'after'     => '</div><!-- .tb-tags (end) -->',
		);

		if ( is_user_logged_in() || $tag_list = bbp_get_topic_tag_list( bbp_get_topic_id(), $tags ) ) {

			echo '<div class="topic-header clearfix">';

			if ( ! empty( $tag_list ) ) {
				echo $tag_list;
			}

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
	 * This method is filtered onto
	 * `bbp_get_displayed_user_field`.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function user_website( $value, $field ) {

		if ( $field == 'description' && ! bbp_is_single_user_edit() && $url = bbp_get_displayed_user_field( 'user_url' ) ) {

			$value .= '</p><p class="bbp-user-forum-role">';
			$value .= themeblvd_get_local( 'website' ) . ': ';

			$url = esc_url( $url );
			$link = sprintf( '<a href="%s" title="%s" target="_blank" rel="nofollow">%s</a>', $url, esc_attr( bbp_get_displayed_user_field( 'display_name' ) ), str_replace( array( 'http://', 'https://' ), '', $url ) );

			$value .= apply_filters( 'themeblvd_bbp_website_link_html', $link, $url );
		}

		return $value;
	}

}
