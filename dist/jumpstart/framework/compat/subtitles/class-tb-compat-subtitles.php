<?php
/**
 * Add extended Subtitles plugin compatibility.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */
class Theme_Blvd_Compat_Subtitles {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.6.0
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
	 * @since 2.6.0
	 */
	public function __construct() {

        $subtitles = Subtitles::getinstance();

        // Add support with Portfolios plugin
		add_post_type_support( 'portfolio_item', 'subtitles' );

        // Remove default styles
		remove_action( 'wp_head', array($subtitles, 'subtitle_styling') );

        // Make sure subtitles only output on single
        // posts and pages.
        add_filter( 'subtitle_view_supported', array($this, 'view') );

        // Make sure no subtitle shows in instances of the title
        // lower in a post, like with the title of the comments,
        // for example.
        add_action( 'themeblvd_single_footer', array($this, 'single_footer') );

	}

    /**
	 * Make sure subtitles only output on single
     * posts and pages.
	 *
	 * @since 2.6.0
	 */
    public function view( $support ) {

        if ( ! is_singular() || themeblvd_get_att('doing_second_loop') ) {
    		$support = false;
    	}

        return $support;
    }

    /**
	 * Make sure no subtitle shows in instances of the title
     * lower in a post, like with the title of the comments,
     * for example.
	 *
	 * @since 2.6.0
	 */
    public function single_footer() {
        add_filter('subtitle_view_supported', '__return_false');
    }

}
