<?php
/**
 * Theme option API helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */

/**
 * Get raw options.
 *
 * This helper function is more for backwards compatibility.
 * Realistically, it doesn't have much use unless an old
 * plugin is still using it.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @return array All raw API options before they've been formatted into theme options.
 */
function themeblvd_get_core_options() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_raw_options();

}

/**
 * Get formatted options. Note that options will not be
 * formatted until after WP's after_setup_theme hook.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @return array All formatted theme options.
 */
function themeblvd_get_formatted_options() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_formatted_options();

}

/**
 * Get theme setting value.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string       $primary   The primary ID of the option.
 * @param  string       $secondary Optional secondary ID to traverse deeper into arrays.
 * @param  string|array $default   Optional default value to be applied if value is empty.
 * @return string|array $setting   Saved setting from theme options.
 */
function themeblvd_get_option( $primary, $secondary = null, $default = null ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$setting = $api->get_setting( $primary, $secondary, $default );

	/**
	 * Filters an individual theme setting returned from
	 * saved theme options.
	 *
	 * @since Theme_Blvd 2.2.0
	 *
	 * @param string|array $setting   Setting value.
	 * @param string       $primary   Primary ID of option.
	 * @param string       $secondary Optional secondary ID to traverse deeper into arrays.
	 * @param string|array $default   Default value to be used if empty.
	 */
	return apply_filters( 'themeblvd_get_option', $setting, $primary, $secondary, $default );

}

/**
 * Check if an option is registered.
 *
 * This can be helpful when theme options have been changed
 * in a theme, but maybe the settings haven't been re-saved
 * by the user.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $option_id ID of the option to check for.
 * @return bool              Whether option exists.
 */
function themeblvd_has_option( $option_id ) {

	$options = themeblvd_get_formatted_options();

	if ( isset( $options[ $option_id ] ) ) {
		return true;
	}

	return false;

}

/**
 * Add theme options tab.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id   ID of tab to add.
 * @param string $tab_name Name of the tab to add.
 * @param bool   $top      Whether the tab should be added to the start or not.
 */
function themeblvd_add_option_tab( $tab_id, $tab_name, $top = false ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_tab( $tab_id, $tab_name, $top );

}

/**
 * Remove theme options tab.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id ID of tab to add.
 */
function themeblvd_remove_option_tab( $tab_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_tab( $tab_id );

}

/**
 * Add theme options section.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id       ID of tab section will be located in
 * @param string $section_id   ID of new section
 * @param string $section_name Name of new section
 * @param string $section_desc Description of new section
 * @param array  $options      Options array formatted for Options Framework
 * @param bool   $top          Whether the option should be added to the top or not
 */
function themeblvd_add_option_section( $tab_id, $section_id, $section_name, $section_desc = null, $options = null, $top = false ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_section(
		$tab_id,
		$section_id,
		$section_name,
		$section_desc,
		$options,
		$top
	);

}

/**
 * Remove theme options section.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab that section to remove belongs to
 * @param string $section_id ID of section to remove
 */
function themeblvd_remove_option_section( $tab_id, $section_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_section( $tab_id, $section_id );

}

/**
 * Add theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to.
 * @param string $section_id ID of section to add to.
 * @param array  $option {
 *     Attributes for option.
 *
 *     @type string $id   Unique ID for option.
 *     @type string $name Title for option.
 *     @type string $desc Description for option.
 *     @type string $std  Default value.
 *     @type string $type Type of option.
 *     ... More attributes, depending on type of option.
 * }
 * @param string $option_id  ID of of your option, note that this id must also be present in $option array.
 */
function themeblvd_add_option( $tab_id, $section_id, $option_id, $option ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_option( $tab_id, $section_id, $option_id, $option );

}

/**
 * Remove theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to.
 * @param string $section_id ID of section to add to.
 * @param string $option_id  ID of of your option.
 */
function themeblvd_remove_option( $tab_id, $section_id, $option_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_option( $tab_id, $section_id, $option_id );

}

/**
 * Remove theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id  ID of of your option
 * @param string $att        Attribute of option to change
 * @param string $value      New value for attribute
 */
function themeblvd_edit_option( $tab_id, $section_id, $option_id, $att, $value ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->edit_option( $tab_id, $section_id, $option_id, $att, $value );

}

/**
 * Get the setting name all theme options are saved to.
 *
 * Usage: $settings = get_option( themeblvd_get_option_name() );
 *
 * For each theme, we use a unique identifier to store
 * the theme's options in the database based on the current
 * name of the theme.
 *
 * This is can be filtered with "themeblvd_option_id". See
 * the get_option_id() method of the Theme_Blvd_Options_API class.
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_get_option_name() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_option_id();

}

/**
 * Get default values for set of options.
 *
 * Note: if you're using this function on the frontend, there
 * will be no filters by default and so no results will get
 * returned. You must call themeblvd_add_saniziation() before
 * calling this function on the frontend.
 *
 * @since Theme_Blvd 2.2.0
 *
 * @param  array $options  Options formatted for internal options framework.
 * @return array $defaults Default values from options.
 */
function themeblvd_get_option_defaults( $options ) {

	if ( ! $options || ! is_array( $options ) ) {
		return array();
	}

	$defaults = array();

	foreach ( $options as $option ) {

		if ( ! isset( $option['id'] ) || ! isset( $option['std'] ) || ! isset( $option['type'] ) ) {
			continue;
		}

		if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) ) {

			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			$defaults[ $option['id'] ] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $option['std'], $option );

		}
	}

	return $defaults;

}

/**
 * Add option presets.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args {
 *     @type string $id      ID of presets section.
 *     @type string $tab     ID of tab preset section will be added to the top of.
 *     @type string $section ID given to section added for presets.
 *     @type array  $sets    Multiple arrays of option values, organized by preset ID.
 * }
 */
function themeblvd_add_option_presets( $args ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_presets( $args );

}

/**
 * Get user-capability for displaying a
 * framework admin page.
 *
 * @link https://codex.wordpress.org/Roles_and_Capabilities
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param  string $page Module ID to check
 * @return string $capability  WordPress capability for current admin module.
 */
function themeblvd_admin_module_cap( $page ) {

	/**
	 * Filters the admin page user capabilities.
	 *
	 * @link https://codex.wordpress.org/Roles_and_Capabilities
	 *
	 * @since Theme_Blvd 2.1.0
	 *
	 * @param array {
	 *     Admin page with capabilities.
	 *
	 *     @type string $builder  Capability for displaying "Templates" page.
	 *     @type string $options  Capability for displaying "Theme Options" page.
	 *     @type string $sidebars Capability for displaying "Widget Areas" page.
	 *     @type string $updates  Capability for displaying "Theme Updates" or "Theme License" page.
	 * }
	 */
	$page_caps = apply_filters( 'themeblvd_admin_module_caps', array(
		'builder'  => 'edit_theme_options', // Role: Administrator
		'options'  => 'edit_theme_options', // Role: Administrator
		'sidebars' => 'edit_theme_options', // Role: Administrator
		'updates'  => 'edit_theme_options', // Role: Administrator
		'base'     => 'edit_theme_options', // Role: Administrator
	) );

	$cap = '';

	if ( isset( $page_caps[ $page ] ) ) {

		$cap = $page_caps[ $page ];

	}

	return $cap;

}

/**
 * Get transparent textures.
 *
 * @since Theme_Blvd 2.0.5
 *
 * @return array $textures Transparent textures.
 */
function themeblvd_get_textures() {

	/**
	 * Filters URL path to where all framework
	 * transparent texture images are located
	 * within the theme.
	 *
	 * @since Theme_Blvd 2.0.5
	 *
	 * @param string URL path.
	 */
	$imagepath = apply_filters(
		'themeblvd_textures_img_path',
		get_template_directory_uri() . '/framework/assets/img/textures/'
	);

	$textures = array(
		'arches' => array(
			'name'      => __( 'Arches', 'jumpstart' ),
			'url'       => $imagepath . 'arches.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '103px 23px',
		),
		'boxy' => array(
			'name'      => __( 'Boxy', 'jumpstart' ),
			'url'       => $imagepath . 'boxy.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'brick_wall' => array(
			'name'      => __( 'Brick Wall', 'jumpstart' ),
			'url'       => $imagepath . 'brick_wall.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_classic' => array(
			'name'      => __( 'Carbon Classic', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_classic.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_diagonal' => array(
			'name'      => __( 'Carbon Diagonal', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_diagonal.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_weave' => array(
			'name'      => __( 'Carbon Weave', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_weave.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'chex' => array(
			'name'      => __( 'Chex', 'jumpstart' ),
			'url'       => $imagepath . 'chex.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'circles' => array(
			'name'      => __( 'Circles', 'jumpstart' ),
			'url'       => $imagepath . 'circles.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'climpek' => array(
			'name'      => __( 'Climpek', 'jumpstart' ),
			'url'       => $imagepath . 'climpek.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'concrete' => array(
			'name'      => __( 'Concrete', 'jumpstart' ),
			'url'       => $imagepath . 'concrete.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'cross' => array(
			'name'      => __( 'Crosses', 'jumpstart' ),
			'url'       => $imagepath . 'cross.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'crystal' => array(
			'name'      => __( 'Crystal', 'jumpstart' ),
			'url'       => $imagepath . 'crystal.png',
			'position'  => '50% 50%',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'cube_stack' => array(
			'name'      => __( 'Cube Stack', 'jumpstart' ),
			'url'       => $imagepath . 'cube_stack.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'denim' => array(
			'name'      => __( 'Denim', 'jumpstart' ),
			'url'       => $imagepath . 'denim.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'diagnol_thin' => array(
			'name'      => __( 'Diagonal (thin)', 'jumpstart' ),
			'url'       => $imagepath . 'diagnol.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '6px 6px',
		),
		'diagnol_thick' => array(
			'name'      => __( 'Diagonal (thick)', 'jumpstart' ),
			'url'       => $imagepath . 'diagnol.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '20px 20px',
		),
		'diamonds' => array(
			'name'      => __( 'Diamonds', 'jumpstart' ),
			'url'       => $imagepath . 'diamonds.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'escheresque' => array(
			'name'      => __( 'Escheresque', 'jumpstart' ),
			'url'       => $imagepath . 'escheresque.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '46px 29px',
			'size'      => 'auto',
		),
		'grid' => array(
			'name'      => __( 'Grid', 'jumpstart' ),
			'url'       => $imagepath . 'grid.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'grunge' => array(
			'name'      => __( 'Grunge', 'jumpstart' ),
			'url'       => $imagepath . 'grunge.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'feather' => array(
			'name'      => __( 'Feather', 'jumpstart' ),
			'url'       => $imagepath . 'feather.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '500px 333px',
		),
		'hexagons' => array(
			'name'      => __( 'Hexagons', 'jumpstart' ),
			'url'       => $imagepath . 'hexagons.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'honey_comb' => array(
			'name'      => __( 'Honey Comb', 'jumpstart' ),
			'url'       => $imagepath . 'honey_comb.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'leather' => array(
			'name'      => __( 'Leather', 'jumpstart' ),
			'url'       => $imagepath . 'leather.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'metal' => array(
			'name'      => __( 'Metal', 'jumpstart' ),
			'url'       => $imagepath . 'metal.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'mosaic' => array(
			'name'      => __( 'Mosaic', 'jumpstart' ),
			'url'       => $imagepath . 'mosaic.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'mosaic_triangles' => array(
			'name'      => __( 'Mosaic Triangles', 'jumpstart' ),
			'url'       => $imagepath . 'mosaic_triangles.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'noise' => array(
			'name'      => __( 'Noise', 'jumpstart' ),
			'url'       => $imagepath . 'noise.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'paper' => array(
			'name'      => __( 'Paper', 'jumpstart' ),
			'url'       => $imagepath . 'paper.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'pixel_weave' => array(
			'name'      => __( 'Pixel Weave', 'jumpstart' ),
			'url'       => $imagepath . 'pixel_weave.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '64px 64px',
		),
		'plaid' => array(
			'name'      => __( 'Plaid', 'jumpstart' ),
			'url'       => $imagepath . 'plaid.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'pyramids' => array(
			'name'      => __( 'Pyramids', 'jumpstart' ),
			'url'       => $imagepath . 'pyramids.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'rubber' => array(
			'name'      => __( 'Rubber', 'jumpstart' ),
			'url'       => $imagepath . 'rubber.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'skulls' => array(
			'name'      => __( 'Skulls', 'jumpstart' ),
			'url'       => $imagepath . 'skulls.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'squares' => array(
			'name'      => __( 'Squares', 'jumpstart' ),
			'url'       => $imagepath . 'squares.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'stacked_circles' => array(
			'name'      => __( 'Stacked Circles', 'jumpstart' ),
			'url'       => $imagepath . 'stacked_circles.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '9px 9px',
		),
		'swirl' => array(
			'name'      => __( 'Swirl', 'jumpstart' ),
			'url'       => $imagepath . 'swirl.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '100px 100px',
		),
		'textile' => array(
			'name'      => __( 'Textile', 'jumpstart' ),
			'url'       => $imagepath . 'textile.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'tiles' => array(
			'name'      => __( 'Tiles', 'jumpstart' ),
			'url'       => $imagepath . 'tiles.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '100px 99px',
		),
		'vertical_fabric' => array(
			'name'      => __( 'Vertical Fabric', 'jumpstart' ),
			'url'       => $imagepath . 'vertical_fabric.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'vintage' => array(
			'name'      => __( 'Vintage', 'jumpstart' ),
			'url'       => $imagepath . 'vintage.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'waves' => array(
			'name'      => __( 'Waves', 'jumpstart' ),
			'url'       => $imagepath . 'waves.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'wood' => array(
			'name'      => __( 'Wood', 'jumpstart' ),
			'url'       => $imagepath . 'wood.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'wood_planks' => array(
			'name'      => __( 'Wood Planks', 'jumpstart' ),
			'url'       => $imagepath . 'wood_planks.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'divider' => array(
			'name'      => __( '---------------', 'jumpstart' ),
			'url'       => null,
			'position'  => null,
			'repeat'    => null,
		),
		'arches_light' => array(
			'name'      => __( 'Light Arches', 'jumpstart' ),
			'url'       => $imagepath . 'arches_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '103px 23px',
		),
		'boxy_light' => array(
			'name'      => __( 'Light Boxy', 'jumpstart' ),
			'url'       => $imagepath . 'boxy_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'brick_wall_light' => array(
			'name'      => __( 'Light Brick Wall', 'jumpstart' ),
			'url'       => $imagepath . 'brick_wall_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_classic_light' => array(
			'name'      => __( 'Light Carbon Classic', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_classic_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_diagonal_light' => array(
			'name'      => __( 'Light Carbon Diagonal', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_diagonal_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'carbon_weave_light' => array(
			'name'      => __( 'Light Carbon Weave', 'jumpstart' ),
			'url'       => $imagepath . 'carbon_weave_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'chex_light' => array(
			'name'      => __( 'Light Chex', 'jumpstart' ),
			'url'       => $imagepath . 'chex_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'circles_light' => array(
			'name'      => __( 'Light Circles', 'jumpstart' ),
			'url'       => $imagepath . 'circles_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'climpek_light' => array(
			'name'      => __( 'Light Climpek', 'jumpstart' ),
			'url'       => $imagepath . 'climpek_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'concrete_light' => array(
			'name'      => __( 'Light Concrete', 'jumpstart' ),
			'url'       => $imagepath . 'concrete_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'cross_light' => array(
			'name'      => __( 'Light Crosses', 'jumpstart' ),
			'url'       => $imagepath . 'cross_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'cube_stack_light' => array(
			'name'      => __( 'Light Cube Stack', 'jumpstart' ),
			'url'       => $imagepath . 'cube_stack_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'denim_light' => array(
			'name'      => __( 'Light Denim', 'jumpstart' ),
			'url'       => $imagepath . 'denim_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'diagnol_thin_light' => array(
			'name'      => __( 'Light Diagonal (thin)', 'jumpstart' ),
			'url'       => $imagepath . 'diagnol_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '6px 6px',
		),
		'diagnol_thick_light' => array(
			'name'      => __( 'Light Diagonal (thick)', 'jumpstart' ),
			'url'       => $imagepath . 'diagnol_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '20px 20px',
		),
		'diamonds_light' => array(
			'name'      => __( 'Light Diamonds', 'jumpstart' ),
			'url'       => $imagepath . 'diamonds_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'escheresque_light' => array(
			'name'      => __( 'Light Escheresque', 'jumpstart' ),
			'url'       => $imagepath . 'escheresque_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '46px 29px',
		),
		'grid_light' => array(
			'name'      => __( 'Light Grid', 'jumpstart' ),
			'url'       => $imagepath . 'grid_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'grunge_light' => array(
			'name'      => __( 'Light Grunge', 'jumpstart' ),
			'url'       => $imagepath . 'grunge_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'feather_light' => array(
			'name'      => __( 'Light Feather', 'jumpstart' ),
			'url'       => $imagepath . 'feather_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '500px 333px',
		),
		'hexagons_light' => array(
			'name'      => __( 'Light Hexagons', 'jumpstart' ),
			'url'       => $imagepath . 'hexagons_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'honey_comb_light' => array(
			'name'      => __( 'Light Honey Comb', 'jumpstart' ),
			'url'       => $imagepath . 'honey_comb_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'leather_light' => array(
			'name'      => __( 'Light Leather', 'jumpstart' ),
			'url'       => $imagepath . 'leather_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'metal_light' => array(
			'name'      => __( 'Light Metal', 'jumpstart' ),
			'url'       => $imagepath . 'metal_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'mosaic_light' => array(
			'name'      => __( 'Light Mosaic', 'jumpstart' ),
			'url'       => $imagepath . 'mosaic_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'mosaic_triangles_light' => array(
			'name'      => __( 'Light Mosaic Triangles', 'jumpstart' ),
			'url'       => $imagepath . 'mosaic_triangles_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'noise_light' => array(
			'name'      => __( 'Light Noise', 'jumpstart' ),
			'url'       => $imagepath . 'noise_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'paper_light' => array(
			'name'      => __( 'Light Paper', 'jumpstart' ),
			'url'       => $imagepath . 'paper_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'pixel_weave_light' => array(
			'name'      => __( 'Light Pixel Weave', 'jumpstart' ),
			'url'       => $imagepath . 'pixel_weave_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '64px 64px',
		),
		'plaid_light' => array(
			'name'      => __( 'Light Plaid', 'jumpstart' ),
			'url'       => $imagepath . 'plaid_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'pyramids_light' => array(
			'name'      => __( 'Light Pyramids', 'jumpstart' ),
			'url'       => $imagepath . 'pyramids_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'rubber_light' => array(
			'name'      => __( 'Light Rubber', 'jumpstart' ),
			'url'       => $imagepath . 'rubber_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'skulls_light' => array(
			'name'      => __( 'Light Skulls', 'jumpstart' ),
			'url'       => $imagepath . 'skulls_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'squares_light' => array(
			'name'      => __( 'Light Squares', 'jumpstart' ),
			'url'       => $imagepath . 'squares_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'stacked_circles_light' => array(
			'name'      => __( 'Light Stacked Circles', 'jumpstart' ),
			'url'       => $imagepath . 'stacked_circles_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '9px 9px',
		),
		'swirl_light' => array(
			'name'      => __( 'Light Swirl', 'jumpstart' ),
			'url'       => $imagepath . 'swirl_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '100px 100px',
		),
		'textile_light' => array(
			'name'      => __( 'Light Textile', 'jumpstart' ),
			'url'       => $imagepath . 'textile_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'tiles_light' => array(
			'name'      => __( 'Light Tiles', 'jumpstart' ),
			'url'       => $imagepath . 'tiles_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => '100px 99px',
		),
		'vertical_fabric_light' => array(
			'name'      => __( 'Light Vertical Fabric', 'jumpstart' ),
			'url'       => $imagepath . 'vertical_fabric_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'vintage_light' => array(
			'name'      => __( 'Light Vintage', 'jumpstart' ),
			'url'       => $imagepath . 'vintage_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'waves_light' => array(
			'name'      => __( 'Light Waves', 'jumpstart' ),
			'url'       => $imagepath . 'waves_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'wood_light' => array(
			'name'      => __( 'Light Wood', 'jumpstart' ),
			'url'       => $imagepath . 'wood_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),
		'wood_planks_light' => array(
			'name'      => __( 'Light Wood Planks', 'jumpstart' ),
			'url'       => $imagepath . 'wood_planks_light.png',
			'position'  => '0 0',
			'repeat'    => 'repeat',
			'size'      => 'auto',
		),

	);

	/**
	 * Filters the available framework transparent
	 * textures to select from.
	 *
	 * Transparent textures can be overlaid on various
	 * other backgrounds in different scenarios setup
	 * from the admin.
	 *
	 * @since Theme_Blvd 2.0.5
	 *
	 * @param array $textures Transparent textures.
	 */
	return apply_filters( 'themeblvd_textures', $textures );

}

/**
 * Get color choices.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  bool  $bootstrap Whether to include Bootstrap colors or not.
 * @param  bool  $custom    Whether to include custom selection.
 * @param  bool  $default   Whether to include a default selection.
 * @return array $colors    Color choices.
 */
function themeblvd_colors( $bootstrap = true, $custom = true, $default = true ) {

	$colors = array(
		'default'    => __( 'Default Color', 'jumpstart' ),
		'custom'     => __( 'Custom Color', 'jumpstart' ),
	);

	$boostrap_colors = array(
		'primary'    => __( 'Primary', 'jumpstart' ),
		'info'       => __( 'Info', 'jumpstart' ),
		'success'    => __( 'Success', 'jumpstart' ),
		'warning'    => __( 'Warning', 'jumpstart' ),
		'danger'     => __( 'Danger', 'jumpstart' ),
	);

	$themeblvd_colors = array(
		'black'      => __( 'Black', 'jumpstart' ),
		'blue'       => __( 'Blue', 'jumpstart' ),
		'dark_blue'  => __( 'Blue (Dark)', 'jumpstart' ),
		'royal'      => __( 'Blue (Royal)', 'jumpstart' ),
		'steel_blue' => __( 'Blue (Steel)', 'jumpstart' ),
		'brown'      => __( 'Brown', 'jumpstart' ),
		'dark_brown' => __( 'Brown (Dark)', 'jumpstart' ),
		'slate_grey' => __( 'Grey (Slate)', 'jumpstart' ),
		'green'      => __( 'Green', 'jumpstart' ),
		'dark_green' => __( 'Green (Dark)', 'jumpstart' ),
		'mauve'      => __( 'Mauve', 'jumpstart' ),
		'orange'     => __( 'Orange', 'jumpstart' ),
		'pearl'      => __( 'Pearl', 'jumpstart' ),
		'pink'       => __( 'Pink', 'jumpstart' ),
		'purple'     => __( 'Purple', 'jumpstart' ),
		'red'        => __( 'Red', 'jumpstart' ),
		'silver'     => __( 'Silver', 'jumpstart' ),
		'teal'       => __( 'Teal', 'jumpstart' ),
		'yellow'     => __( 'Yellow', 'jumpstart' ),
		'wheat'      => __( 'Wheat', 'jumpstart' ),
		'white'      => __( 'White', 'jumpstart' ),
	);

	if ( $bootstrap ) {

		$colors = array_merge( $colors, $boostrap_colors, $themeblvd_colors );

	} else {

		$colors = array_merge( $colors, $themeblvd_colors );

	}

	if ( ! $custom ) {

		unset( $colors['custom'] );

	}

	if ( ! $default ) {

		unset( $colors['default'] );

	}

	/**
	 * Filters the framework preset color choices.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array $colors    Color choices.
	 * @param bool  $bootstrap Whether to include Bootstrap colors or not.
	 * @param bool  $custom    Whether to include custom selection.
	 * @param bool  $default   Whether to include a default selection.
	 */
	return apply_filters( 'themeblvd_colors', $colors, $bootstrap, $custom, $default );

}

/**
 * Set up WordPress conditional assignments.
 *
 * This was crated with the intention of being
 * used with the Theme Blvd Widget Areas plugin,
 * to assign custom sidebars to specific pages
 * of a website.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @return array $conditionals Conditional assignments.
 */
function themeblvd_conditionals_config() {

	$conditionals = array(
		'pages' => array(
			'id'    => 'pages',
			'name'  => __( 'Pages', 'jumpstart' ),
			'empty' => __( 'No pages to display.', 'jumpstart' ),
			'field' => 'page',
		),
		'posts' => array(
			'id'    => 'posts',
			'name'  => __( 'Posts', 'jumpstart' ),
			'empty' => __( 'No posts to display.', 'jumpstart' ),
			'field' => 'post',
		),
		'posts_in_category' => array(
			'id'    => 'posts_in_category',
			'name'  => __( 'Posts in Category', 'jumpstart' ),
			'empty' => __( 'No categories to display.', 'jumpstart' ),
			'field' => 'posts_in_category',
		),
		'categories' => array(
			'id'    => 'categories',
			'name'  => __( 'Category Archives', 'jumpstart' ),
			'empty' => __( 'No categories to display.', 'jumpstart' ),
			'field' => 'category',
		),
		'tags' => array(
			'id'    => 'tags',
			'name'  => __( 'Tag Archives', 'jumpstart' ),
			'empty' => __( 'No tags to display.', 'jumpstart' ),
			'field' => 'tag',
		),
		'portfolio_items' => array(
			'id'    => 'portfolio_items',
			'name'  => __( 'Single Portfolio Items', 'jumpstart' ),
			'empty' => __( 'No portfolio items to display.', 'jumpstart' ),
			'field' => 'portfolio_item',
		),
		'portfolio_items_in_portfolio' => array(
			'id'    => 'portfolio_items_in_portfolio',
			'name'  => __( 'Items in Portfolio', 'jumpstart' ),
			'empty' => __( 'No categories to display.', 'jumpstart' ),
			'field' => 'portfolio_items_in_portfolio',
		),
		'portfolios' => array(
			'id'    => 'portfolios',
			'name'  => __( 'Portfolios', 'jumpstart' ),
			'empty' => __( 'No portfolios to display.', 'jumpstart' ),
			'field' => 'portfolio',
		),
		'portfolio_tags' => array(
			'id'    => 'portfolio_tags',
			'name'  => __( 'Portfolio Tag Archives', 'jumpstart' ),
			'empty' => __( 'No portfolio tags to display.', 'jumpstart' ),
			'field' => 'portfolio_tag',
		),
		'portfolio_top' => array(
			'id'    => 'portfolio_top',
			'name'  => __( 'Portfolio Hierarchy', 'jumpstart' ),
			'empty' => null,
			'field' => 'portfolio_top',
			'items' => array(
				'portfolio_items' => __( 'All single portfolio items', 'jumpstart' ),
				'portfolios'      => __( 'Items displayed by portfolio', 'jumpstart' ),
				'portfolio_tags'  => __( 'Items displayed by portfolio tag', 'jumpstart' ),
			),
		),
		'product_cat' => array(
			'id'    => 'product_cat',
			'name'  => __( 'Product Category Archives', 'jumpstart' ),
			'empty' => __( 'No categories to display.', 'jumpstart' ),
			'field' => 'product_cat',
		),
		'product_tags' => array(
			'id'    => 'product_tags',
			'name'  => __( 'Product Tag Archives', 'jumpstart' ),
			'empty' => __( 'No tags to display.', 'jumpstart' ),
			'field' => 'product_tag',
		),
		'products_in_cat' => array(
			'id'    => 'products_in_cat',
			'name'  => __( 'Products in Category', 'jumpstart' ),
			'empty' => __( 'No categories to display.', 'jumpstart' ),
			'field' => 'products_in_cat',
		),
		'product_top' => array(
			'id'    => 'product_top',
			'name'  => __( 'Product Hierarchy', 'jumpstart' ),
			'empty' => null,
			'field' => 'product_top',
			'items' => array(
				'woocommerce'    => __( 'All WooCommerce pages', 'jumpstart' ),
				'products'       => __( 'All single products', 'jumpstart' ),
				'product_cat'    => __( 'Products displayed by category', 'jumpstart' ),
				'product_tag'    => __( 'Products displayed by tag', 'jumpstart' ),
				'product_search' => __( 'WooCommerce search results', 'jumpstart' ),
			),
		),
		'forums' => array(
			'id'    => 'forums',
			'name'  => __( 'Forums', 'jumpstart' ),
			'empty' => null,
			'field' => 'forum',
		),
		'forum_top' => array(
			'id'    => 'forum_top',
			'name'  => __( 'Forum Hierarchy', 'jumpstart' ),
			'empty' => null,
			'field' => 'forum_top',
			'items' => array(
				'bbpress'         => __( 'All bbPress pages', 'jumpstart' ),
				'topic'           => __( 'All single topics', 'jumpstart' ),
				'forum'           => __( 'All single forums', 'jumpstart' ),
				'topic_tag'       => __( 'Viewing topics by tag', 'jumpstart' ),
				'forum_user'      => __( 'Public user profiles', 'jumpstart' ),
				'forum_user_home' => __( 'Logged-in user home', 'jumpstart' ),
			),
		),
		'top' => array(
			'id'    => 'top',
			'name'  => __( 'Hierarchy', 'jumpstart' ),
			'empty' => null,
			'field' => 'top',
			'items' => array(
				'home'       => __( 'Homepage', 'jumpstart' ),
				'posts'      => __( 'All posts (any post type)', 'jumpstart' ),
				'blog_posts' => __( 'All blog posts', 'jumpstart' ),
				'pages'      => __( 'All pages', 'jumpstart' ),
				'archives'   => __( 'All archives', 'jumpstart' ),
				'categories' => __( 'All category archives', 'jumpstart' ),
				'tags'       => __( 'All tag archives', 'jumpstart' ),
				'authors'    => __( 'All author archives', 'jumpstart' ),
				'search'     => __( 'Search Results', 'jumpstart' ),
				'404'        => __( '404 Page', 'jumpstart' ),
			),
		),
		'custom' => array(
			'id'    => 'custom',
			'name'  => __( 'Custom', 'jumpstart' ),
			'empty' => null,
			'field' => 'custom',
		),
	);

	if ( ! themeblvd_installed( 'portfolios' ) ) {

		unset( $conditionals['portfolio_items'], $conditionals['portfolio_items_in_portfolio'], $conditionals['portfolios'], $conditionals['portfolio_tags'], $conditionals['portfolio_top'] );

	}

	if ( ! themeblvd_installed( 'woocommerce' ) ) {

		unset( $conditionals['product_cat'], $conditionals['product_tags'], $conditionals['products_in_cat'], $conditionals['product_top'] );

	}

	if ( ! themeblvd_installed( 'bbpress' ) ) {

		unset( $conditionals['forums'], $conditionals['forum_top'] );

	}

	/**
	 * Filters the data used to setup WordPress
	 * conditional assignment options.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @return array $conditionals Conditional assignments.
	 */
	return apply_filters( 'themeblvd_conditionals_config', $conditionals );

}

/**
 * Get full-width option types.
 *
 * This list of option types will be used to
 * determine if an option displays full-width,
 * with the description appearing below, in a
 * given options interface instance.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @return array $types All option types to display as full-width.
 */
function themeblvd_get_full_width_option_types() {

	/**
	 * Filters which option types will be displayed as
	 * full-width in an options set.
	 *
	 * Specifically, this refers to the layout of an option
	 * where you have the option controls to the left and
	 * the description to the right. With these, option
	 * controls will be stretched full-width of the options
	 * panel, with the description falling below.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param array All option types to display as full-width.
	 */
	$types = apply_filters( 'themeblvd_full_width_option_types', array(
		'bars',
		'buttons',
		'code',
		'editor',
		'datasets',
		'locations',
		'price_cols',
		'sectors',
		'tabs',
		'testimonials',
		'text_blocks',
		'toggles',
	) );

	return $types;

}

/**
 * Whether to allow rich-editing throughout
 * option interfaces with `editor` type
 * option.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param bool $do Whether to allow rich editing.
 */
function themeblvd_do_rich_editing() {

	$do = false;

	if ( user_can_richedit() ) {

		$do = true;

	}

	/**
	 * Filters whether rich-editing is added
	 * throughout option interfaces.
	 *
	 * When FALSE, all `editor` type framework
	 * options will be rendered as a standard
	 * <textarea>.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param bool $do Whether to allow rich editing.
	 */
	return apply_filters( 'themeblvd_do_rich_editing', $do );

}
