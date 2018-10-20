<?php
/**
 * Template Name: Redirect
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * Usage Instructions:
 *
 * 1. Create a new page.
 * 2. Add a title to the page.
 * 3. Add an URL to the content of the page (e.g. http://www.google.com OR google.com OR www.google.com)
 * 4. Select "Redirect" as the page template
 * 5. Publish!
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
?><!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php if ( have_posts() ) : the_post(); ?>

	<?php
	$url = get_the_content();

	if ( ! preg_match( '/^http:\/\//', $url ) && ! preg_match( '/^https:\/\//', $url ) ) {

		$url = 'http://' . $url;

	}
	?>

	<head>

		<meta http-equiv="Refresh" content="0; url=<?php echo esc_url( $url ); ?>">

	</head>

	<body></body>

<?php endif; ?>

</html>
