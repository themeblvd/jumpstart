<?php
/**
 * Add options to WP edit tag and category
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Tax_Options {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
	 * Stored tax meta data
	 *
	 * @since 2.5.0
	 */
	private $meta = array();

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Tax_Options A single instance of this class.
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

		$taxos = apply_filters( 'themeblvd_add_tax_options_to', array('category', 'post_tag') );

		foreach ( $taxos as $tax ) {

			$this->meta[$tax] = array();

			if ( is_admin() ) {
				add_action( "{$tax}_edit_form_fields", array($this, 'edit'), 10, 2 );
				add_action( 'edited_term', array($this, 'save'), 10, 3 );
			}

		}

	}

	/**
	 * Geta meta data value.
	 *
	 * @since 2.5.0
	 *
	 * @param string $tax Taxonomy that the term belongs to.
	 * @param string $term Specific term for that taxonomy.
	 * @param string $key Option key to pull for meta.
	 * @param string $default Default value, to return if no value is found.
	 * @return string Value from meta data.
	 */
	public function get( $tax, $term, $key, $default = null ) {

		if ( isset($this->meta[$tax][$term]) ) {
			$meta = $this->meta[$tax][$term];
		} else {
			$meta = get_option("tb_meta_{$tax}_{$term}");
		}

		if ( isset($meta[$key]) ) {
			return $meta[$key];
		}

		if ( $default ) {
			return $default;
		}

		return '';
	}

	/**
	 * Display options when editing term.
	 *
	 * @since 2.5.0
	 *
	 * @param string $tax Taxonomy that the term belongs to.
	 * @param string $term Specific term for that taxonomy.
	 */
	public function edit( $term, $taxonomy ) {

		// Sidebar Layouts
		$layouts = themeblvd_sidebar_layouts();
		$select_layouts = array('default' => __('Use default setting', 'jumpstart'));

		foreach ( $layouts as $layout ) {
			$select_layouts[$layout['id']] = $layout['name'];
		}

		// Post Display modes
		$select_modes = array_merge( array('default' => __('Use default setting', 'jumpstart')), themeblvd_get_modes() );

		// Values
		$sidebar_layout = $this->get( $taxonomy, $term->slug, 'sidebar_layout', 'default' );
		$info = $this->get( $taxonomy, $term->slug, 'info', 'default' );
		$mode = $this->get( $taxonomy, $term->slug, 'mode', 'default' );
		?>
		<tr class="form-field">
			<th scope="row"><label for="_tb_info"><?php esc_html_e('Archive Info Box', 'jumpstart'); ?></label></th>
			<td>
				<select name="_tb_info" id="_tb_info" class="postform">
					<option value="default" <?php selected($info, 'default'); ?>><?php esc_html_e('Use default setting', 'jumpstart'); ?></option>
					<option value="show" <?php selected($info, 'show'); ?>><?php esc_html_e('Show', 'jumpstart'); ?></option>
					<option value="hide" <?php selected($info, 'hide'); ?>><?php esc_html_e('Hide', 'jumpstart'); ?></option>
				</select>
				<p class="description"><?php printf(esc_html__('Select if you\'d like to display an info box with Name and Description of this %s at the top of its archives.', 'jumpstart'), $taxonomy); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="_tb_sidebar_layout"><?php esc_html_e('Archive Sidebar Layout', 'jumpstart'); ?></label></th>
			<td>
				<select name="_tb_sidebar_layout" id="_tb_sidebar_layout" class="postform">
					<?php foreach ( $select_layouts as $key => $value ) : ?>
						<option value="<?php echo $key; ?>" <?php selected($sidebar_layout, $key); ?>><?php echo esc_html($value); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php printf(esc_html__('Select the sidebar layout used in displaying archives for this %s.', 'jumpstart'), $taxonomy); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="_tb_mode"><?php esc_html_e('Archive Post Display', 'jumpstart'); ?></label></th>
			<td>
				<select name="_tb_mode" id="_tb_mode" class="postform">
					<?php foreach ( $select_modes as $key => $value ) : ?>
						<option value="<?php echo $key; ?>" <?php selected($mode, $key); ?>><?php echo esc_html($value); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php printf(esc_html__('Select how posts are displayed on archives for this %s.', 'jumpstart'), $taxonomy); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save meta data for term edit page.
	 *
	 * @since 2.5.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function save( $term_id, $tt_id, $tax ) {

		global $_POST;

		$term = get_term_by( 'id', $term_id, $tax );
		$term = $term->slug;

		$meta = get_option( "tb_meta_{$tax}_{$term}", array() );

		if ( isset( $_POST['_tb_info'] ) ) {
			$meta['info'] = wp_kses( $_POST['_tb_info'], array() );
		}

		if ( isset( $_POST['_tb_sidebar_layout'] ) ) {
			$meta['sidebar_layout'] = wp_kses( $_POST['_tb_sidebar_layout'], array() );
		}

		if ( isset( $_POST['_tb_mode'] ) ) {
			$meta['mode'] = wp_kses( $_POST['_tb_mode'], array() );
		}

		update_option("tb_meta_{$tax}_{$term}", $meta);

	}

}

/**
 * Get tax meta. Provide a helper shell for the
 * frontend template files.
 *
 * @since 2.5.0
 *
 * @param string $tax Taxonomy slug.
 * @param string $term Term taxonomy slug.
 * @param string $key Option key to pull for meta.
 * @param string $default Default value, to return if no value is found.
 */
function themeblvd_get_tax_meta( $tax, $term, $key, $default = null ) {
	$tax_options = Theme_Blvd_Tax_Options::get_instance();
	return $tax_options->get($tax, $term, $key, $default);
}
