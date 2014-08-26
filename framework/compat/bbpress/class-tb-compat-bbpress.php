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
		add_filter( 'bbp_register_forum_post_type', array($this, 'register_post_types') );
        add_filter( 'bbp_register_topic_post_type', array($this, 'register_post_types') );
        add_filter( 'bbp_register_reply_post_type', array($this, 'register_post_types') );
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
}