<?php
/**
 * Run Layout Builder
 * 
 * We check the user-role before running the 
 * builder framework.
 *
 * @since 2.0.0
 */

function builder_blvd_rolescheck () {
	if ( themeblvd_supports( 'admin', 'builder' ) && current_user_can( themeblvd_admin_module_cap( 'builder' ) ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'builder_blvd_add_page');
		add_action( 'admin_init', 'builder_blvd_init' );
	}
}
add_action( 'init', 'builder_blvd_rolescheck' );

/**
 * Initiate builder framework
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_init' ) ) {
	function builder_blvd_init() {
	
		// Include the required files
		// require_once dirname( __FILE__ ) . '/builder-default.php'; // No longer needed as of v2.0.6
		require_once dirname( __FILE__ ) . '/builder-samples.php';
		require_once dirname( __FILE__ ) . '/builder-interface.php';
		require_once dirname( __FILE__ ) . '/builder-ajax.php';
			
	}
}

/**
 * Add a menu page for Builder
 *
 * @since 2.0.0 
 */

if ( ! function_exists( 'builder_blvd_add_page' ) ) {
	function builder_blvd_add_page() {
	
		$bb_page = add_object_page( 'Layout Builder', 'Builder', themeblvd_admin_module_cap( 'builder' ), 'builder_blvd', 'builder_blvd_page', 'div', 30 );
		
		// Adds actions to hook in the required css and javascript
		add_action( 'admin_print_styles-'.$bb_page, 'optionsframework_load_styles' );
		add_action( 'admin_print_scripts-'.$bb_page, 'optionsframework_load_scripts' );
		add_action( 'admin_print_styles-'.$bb_page, 'builder_blvd_load_styles' );
		add_action( 'admin_print_scripts-'.$bb_page, 'builder_blvd_load_scripts' );
		add_action( 'admin_print_styles-'.$bb_page, 'optionsframework_mlu_css', 0 );
		add_action( 'admin_print_scripts-'.$bb_page, 'optionsframework_mlu_js', 0 );
		
	}
}

/**
 * Loads the CSS
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_load_styles' ) ) {
	function builder_blvd_load_styles() {
		wp_enqueue_style('sharedframework-style', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'css/admin-style.css', null, TB_FRAMEWORK_VERSION);
		wp_enqueue_style('builderframework-style', BUILDER_FRAMEWORK_DIRECTORY . 'css/builder-style.css', null, TB_FRAMEWORK_VERSION);
	}
}	

/**
 * Loads the javascript
 *
 * @since 2.0.0 
 */

if( ! function_exists( 'builder_blvd_load_scripts' ) ) {
	function builder_blvd_load_scripts() {
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('sharedframework-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/shared.js', array('jquery'), TB_FRAMEWORK_VERSION);
		wp_enqueue_script('builderframework-scripts', BUILDER_FRAMEWORK_DIRECTORY . 'js/builder.js', array('jquery'), TB_FRAMEWORK_VERSION);
		wp_localize_script('sharedframework-scripts', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
	}
}

/**
 * Builds out the header for all builder pages.
 *
 * @since 2.0.0
 */

if ( ! function_exists( 'builder_blvd_page_header' ) ) {
	function builder_blvd_page_header() {
		?>
		<div id="builder_blvd">
			<div id="optionsframework" class="wrap">
				<div class="admin-module-header">
			    	<?php do_action( 'themeblvd_admin_module_header', 'builder' ); ?>
			    </div>
			    <?php screen_icon( 'tb_builder' ); ?>
			    <h2 class="nav-tab-wrapper">
			        <a href="#manage_layouts" id="manage_layouts-tab" class="nav-tab" title="<?php _e( 'Manage Layouts', 'themeblvd' ); ?>"><?php _e( 'Manage Layouts', 'themeblvd' ); ?></a>
			        <a href="#add_layout" id="add_layout-tab" class="nav-tab" title="<?php _e( 'Add New Layout', 'themeblvd' ); ?>"><?php _e( 'Add New Layout', 'themeblvd' ); ?></a>
			        <a href="#edit_layout" id="edit_layout-tab" class="nav-tab nav-edit-builder" title="<?php _e( 'Edit Layout', 'themeblvd' ); ?>"><?php _e( 'Edit Layout', 'themeblvd' ); ?></a>
			    </h2>
	    <?php
	}	
}

/**
 * Builds out the footer for all builder pages.
 *
 * @since 2.0.0 
 */

if ( ! function_exists( 'builder_blvd_page_footer' ) ) {
	function builder_blvd_page_footer() {
		?>
				<div class="admin-module-footer">
			    	<?php do_action( 'themeblvd_admin_module_footer', 'builder' ); ?>
			    </div>
			</div><!-- #optionsframework (end) -->
		</div><!-- #builder_blvd (end) -->
	    <?php
	}	
}

/**
 * Builds out the full admin page.
 *
 * @since 2.0.0 
 */

if ( ! function_exists( 'builder_blvd_page' ) ) {
	function builder_blvd_page() {

		builder_blvd_page_header();
		?>
    	<!-- MANAGE LAYOUT (start) -->
    	
    	<div id="manage_layouts" class="group">
	    	<form id="manage_builder">	
	    		<?php 
	    		$manage_nonce = wp_create_nonce( 'optionsframework_manage_builder' );
				echo '<input type="hidden" name="option_page" value="optionsframework_manage_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$manage_nonce.'" />';
				?>
				<div class="ajax-mitt"><?php builder_blvd_manage(); ?></div>
			</form><!-- #manage_builder (end) -->
		</div><!-- #manage (end) -->
		
		<!-- MANAGE LAYOUT (end) -->
		
		<!-- ADD LAYOUT (start) -->
		
		<div id="add_layout" class="group">
			<form id="add_new_builder">
				<?php
				$add_nonce = wp_create_nonce( 'optionsframework_new_builder' );
				echo '<input type="hidden" name="option_page" value="optionsframework_add_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$add_nonce.'" />';
				builder_blvd_add( null );
				?>
			</form><!-- #add_new_builder (end) -->
		</div><!-- #manage (end) -->
		
		<!-- ADD LAYOUT (end) -->
		
		<!-- EDIT LAYOUT (start) -->
		
		<div id="edit_layout" class="group">
			<form id="edit_builder" method="post">
				<?php
				$edit_nonce = wp_create_nonce( 'optionsframework_save_builder' );
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="option_page" value="optionsframework_edit_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$edit_nonce.'" />';
				?>
				<div class="ajax-mitt"><!-- AJAX inserts edit builder page here. --></div>				
			</form>
		</div><!-- #manage (end) -->
	
		<!-- EDIT LAYOUT (end) -->
		<?php
		builder_blvd_page_footer();
	}
}

/**
 * Sample layout previews when selecting one.
 *
 * @since 2.0.0
 *
 * @return string $output HTML to display
 */

if( ! function_exists( 'builder_blvd_sample_previews' ) ) {
	function builder_blvd_sample_previews() {
		
		// Get sample layouts
		$samples = builder_blvd_samples();
		
		// Construct output
		$output = '<div class="sample-layouts">';
		foreach( $samples as $sample ) {
			$output .= '<div id="sample-'.$sample['id'].'">';
			$output .= '<img src="'.$sample['preview'].'" />';
			if( isset( $sample['credit'] ) )
				$output .= '<p class="note">'.$sample['credit'].'</p>';
			$output .= '</div>';
		}
		$output .= '</div><!-- .sample-layouts (end) -->';
		
		return $output;
	}
}