<?php
/**
 * Theme Blvd Envato Updates
 *
 * Allows current theme to be updated from ThemeForest
 * marketplace through WordPress theme updater system
 * and Envato API.
 */
class Theme_Blvd_Envato_Updates {

	protected $args;

	/**
	 * Constructor.
	 *
	 * @param array $args Envato and theme details for updating
	 */
	public function __construct( $args ) {

		// Debug
		// set_site_transient( 'update_themes', null );

		// Arguments
		$defaults = array(
			'envato_username'	=> '',
			'envato_api_key'	=> '',
			'author_name'		=> 'Jason Bobich',
			'backup'			=> true
		);
		$this->args = wp_parse_args( $args, $defaults );

		// Add in our check for updates if we have all required arguments.
		if ( $this->args['envato_username'] && $this->args['envato_api_key'] && $this->args['author_name'] ) {

			// Check for updates with the standard WP system under Appearance > Themes
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_updates' ) );

			// Add backups if enabled
			if ( $this->args['backup'] && $this->args['backup'] != 'no' ) {
				add_filter( 'upgrader_pre_install', array( $this, 'backup_theme' ) );
			}

		}
	}

	/**
	 * Check for theme updates from Envato.
	 *
	 * @param array $args Envato and theme details for updating
	 */
	public function check_for_updates( $updates ) {

		// Only continue if this is our second time
		// running through the filter.
		if ( ! isset( $updates->checked ) ) {
			return $updates;
		}

		// If user can't install themes, this shouldn't
		// be happenning.
		if ( ! current_user_can( 'install_themes' ) ) {
			return $updates;
		}

		// Temporarily increase http_request_timeout to 300 seconds.
		add_filter( 'http_request_timeout', array( $this, 'bumb_request_timeout' ) );

		// Tap into Envato API
		$envato_api = new Envato_Protected_API( $this->args['envato_username'], $this->args['envato_api_key'] );

		// Get themes purchased from this Envato user and re-format
		// as an array we can use to pull directly from.
		$purchased_themes = array();
		$purchased = $envato_api->wp_list_themes( true );

		if ( ! empty( $purchased ) ) {
			foreach ( $purchased as $theme ) {

				// Check if Envato has temporarily locked us out
				if ( ! is_object( $theme ) ) {
					return $updates;
				}

				if ( in_array( $theme->author_name, array($this->args['author_name'], 'Jason Bobich', 'Theme Blvd') ) ) {
					$purchased_themes[$theme->theme_name] = array(
						'item_id' 		=> $theme->item_id,
						'author_name' 	=> $theme->author_name,
						'version' 		=> $theme->version
					);
				}
			}
		}

		// Get themes currently present in WordPress
		$installed_themes = wp_get_themes();

		// Loop through current themes in WP directory and
		// check if we need to apply our updates.
		if ( $installed_themes ) {
			foreach ( $installed_themes as $installed_theme_id => $installed_theme ) {

				// Make sure the current installed theme is one of
				// the themes purchased from the current author.
				if ( isset( $purchased_themes[$installed_theme->Name] ) ) {

					$current_theme = $purchased_themes[$installed_theme->Name];

					if ( version_compare( $installed_theme->Version, $current_theme['version'], '<' ) ) {
						if ( $url = $envato_api->wp_download( $current_theme['item_id'] ) ) {

							// Get update rolling with WP
							$updates->response[$installed_theme->Stylesheet] = array(
								'url' 			=> apply_filters( 'themeblvd_envato_updates_changelog', 'http://themeblvd.com/changelog/?theme='.$installed_theme->Template, $installed_theme, $current_theme ),
								'new_version' 	=> $current_theme['version'],
								'old_version'	=> $installed_theme->Version,
								'package' 		=> $url,
								'type'			=> apply_filters( 'themeblvd_envato_updates_type', 'themeblvd-envato' )
							);

						}
					}
				}
			}
		}

		// Put http_request_timeout back.
		remove_filter( 'http_request_timeout', array( $this, 'bumb_request_timeout' ) );

		return $updates;
	}

	/**
	 * Backup previous version of theme.
	 *
	 * @param string
	 */
	public function backup_theme() {

		global $wp_filesystem;

		// Are we currently updating a theme?
		if ( ! isset($_REQUEST['action']) || $_REQUEST['action'] != 'upgrade-theme' ) {
			return;
		}

		// Update info
		$theme = $_REQUEST['theme'];
		$current_update = get_site_transient( 'update_themes' );
		$current_update = $current_update->response[$theme];

		// Is this one of our themes?
		$type = apply_filters( 'themeblvd_envato_updates_type', 'themeblvd-envato' );
		if ( ! isset( $current_update['type'] ) || $current_update['type'] != $type ) {
			return;
		}

		// Theme data
		$theme_data = wp_get_theme($theme);
		$theme_name = $theme_data->Name;
		$old_version = isset( $current_update['old_version'] ) ? $current_update['old_version'] : null;

		// Start the process
		show_message( sprintf( esc_html__('Backing up %2$s v%1$s before udpating to new version...', '@@text-domain' ), $old_version, $theme_name ) );

		// Make sure the themes directory can be found.
		$themes_directory = WP_CONTENT_DIR . '/themes';
		if ( ! $wp_filesystem->find_folder( $themes_directory ) ) {
			wp_die( esc_html__('Unable to locate WordPress Theme directory.', '@@text-domain') );
		}

		// Locations
		$from = $themes_directory.'/'.$theme;
		$to = $from.'-'.$old_version;

		// Create destination if needed
		if ( ! $wp_filesystem->exists( $to ) ) {
			if ( ! $wp_filesystem->mkdir( $to, FS_CHMOD_DIR ) ) {
				show_message( esc_html__('Could not create directory for backup.', '@@text-domain') );
				wp_die();
			}
		}

		// Make copy
		$result = copy_dir( $from, $to );
		if ( is_wp_error( $result ) ) {
			// @todo Can we delete temporary downloaded theme at /upgrades/ if error?
			wp_die($result->get_error_message());
		}

		// End the process
		$backup_path = '<code>/wp-content/themes/'.$theme.'-'.$old_version.'/</code>'; // just for display purposes
		show_message( sprintf( esc_html__('%2$s v%1$s has been backed up sucessfully to %3$s...', '@@text-domain' ), $old_version, $theme_name, $backup_path ) );

	}

	/**
	 * Temporaritly add to http_request_timeout filter to force timeout at
	 * 300 seconds for pulling from Envato API.
	 *
	 * @return int 300
	 */
	public function bumb_request_timeout( $timeout ) {
		return apply_filters( 'themeblvd_envato_http_request_timeout', 300 );
	}

}
