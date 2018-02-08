<?php
/**
 * Template for displaying the license admin page.
 *
 * @since Jump_Start 2.2.2
 */
?>
<div class="wrap jumpstart-license-admin">

	<h2>
		<?php
		// translators: 1. item name
		printf( __( '%s Licenses', 'jumpstart' ), $this->item_name );
		?>
	</h2>

	<p>
		<?php
		printf(
			// translators: 1. item name, 2. extentions link URL, 3. extensions link title
			__( 'Enter your license keys here to receive updates for %s and purchased <a href="%s" title="%s" target="_blank">extensions</a>.', 'jumpstart' ),
			$this->item_name,
			'https://wpjumpstart.com/extensions?utm_campaign=admin&utm_source=licenses&utm_medium=description',
			__( 'Browse Extensions', 'jumpstart' )
		);
		?>
	</p>

	<form method="post" action="options.php">

		<?php settings_fields( $this->screen_id ); ?>

		<table class="form-table">

			<tbody>

				<?php
				$this->license_row( array(
					'item_name'      => $this->item_name,
					'item_shortname' => $this->item_shortname,
				) );
				?>

				<?php foreach ( $this->extensions as $extension ) : ?>

					<?php $this->license_row( $extension ); ?>

				<?php endforeach; ?>

			</tbody>

		</table>

		<?php submit_button(); ?>

	</form>
