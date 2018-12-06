<?php
/**
 * The default template for displaying content
 * in a mini post grid.
 *
 * @link https://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

if ( themeblvd_get_att( 'gallery' ) ) {

	themeblvd_the_post_thumbnail(
		'tb_thumb',
		array(
			'attachment_id' => get_the_ID(),
			'link'          => 'thumbnail',
		)
	);

} else {

	themeblvd_the_post_thumbnail(
		'tb_thumb',
		array(
			'link' => 'post',
		)
	);

}
