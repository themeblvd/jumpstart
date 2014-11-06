<?php
/**
 * Template Name: Blank Page
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via hooks, filters, and
 * template parts.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
?><html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/framework/assets/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class('blank-page'); ?>>

	<?php themeblvd_before(); ?>

	<div id="blank-page">
		<div class="wrap">

			<div id="content" class="clearfix" role="main">
				<div class="inner">
					<?php themeblvd_content_top(); ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', themeblvd_get_part( 'page' ) ); ?>
					<?php endwhile; ?>

					<?php themeblvd_content_bottom(); ?>
				</div><!-- .inner (end) -->
			</div><!-- #content (end) -->

		</div><!-- .wrap (end) -->
	</div><!-- #blank-page (end) -->

	<?php themeblvd_after(); ?>
	<?php wp_footer(); ?>

</body>
</html>