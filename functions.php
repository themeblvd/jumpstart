<?php
/*-----------------------------------------------------------------------------------*/
/* Run ThemeBlvd Framework
/* 
/* Below is the file needed to load the parent theme and theme framework. 
/* It's included with require_once(). 
/*
/* If you're creating a child theme, this line needs to be at the top of your 
/* child theme's functions.php. By doing this you're overriding the file being 
/* included here.
/*-----------------------------------------------------------------------------------*/

require_once ( get_template_directory() . '/framework/themeblvd.php' );


// Slide types - Video only
$slide_types = array( 'video' );
 
// Media positions - Full Width only
$media_positions = array( 'full' => '' );
 
// Slide elements - none
$slide_elements = array();
 
// Options
$options = array(
	array(
		'id'		=> 'fx',
		'name'		=> 'How to transition between slides?',
		'std'		=> 'fade',
		'type'		=> 'select',
		'options'	=> array(
			'fade' 	=> 'Fade',
			'slide'	=> 'Slide'
		)
	),
	array(
		'id'		=> 'prev',
		'name' 		=> 'Text for "Previous" Button',
		'std'		=> 'Previous Video',
		'type'		=> 'text'
	),
	array(
		'id'		=> 'next',
		'name' 		=> 'Text for "Next" Button',
		'std'		=> 'Next Video',
		'type'		=> 'text'
	)
);
 
// Add Slider type
themeblvd_add_slider( 'simple_video_slider', 'Simple Video Slider', $slide_types, $media_positions, $slide_elements, $options, 'display_simple_video_slider' );
