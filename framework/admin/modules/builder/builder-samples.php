<?php
/**
 * Get all sample layouts.
 *
 * @since 2.0.0
 *
 * @return array
 */

if( ! function_exists( 'builder_blvd_samples' ) ) {
	function builder_blvd_samples() {
	
		global $_themeblvd_user_sample_layouts;
		global $_themeblvd_remove_sample_layouts;
		
		// Path to images used in sample layouts on frontend.
		$imgpath = TB_FRAMEWORK_DIRECTORY.'/frontend/assets/images/sample';
		
		$samples = array(
			'business_1' => array(
				'name' => 'Business Homepage #1',
				'id' => 'business_1',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-business_1.png',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					)
				),
				'primary' => array(
					'element_2' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
	                        'button' => 1,
	                        'button_text' => 'Get Started Today!',
	                        'button_color' => 'default',
	                        'button_url' => 'http://www.google.com',
	                        'button_target' => '_blank'
						)
					),
					'element_3' => array(
	                    'type' => 'columns',
	                    'query_type' => 'none',
	                    'options' => array(
	                        'setup' => array(
								'num' => '3',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_4-grid_4-grid_4',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Sample Headline #1</h3>\n\n<img src=\"$imgpath/business_1.jpg\" />\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n[button link=\"http://google.com\" color=\"black\"]Learn More[/button]",
								'raw_format' => 1
							),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Sample Headline #2</h3>\n\n<img src=\"$imgpath/business_2.jpg\" />\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n[button link=\"http://google.com\" color=\"black\"]Learn More[/button]",
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Sample Headline #3</h3>\n\n<img src=\"$imgpath/business_3.jpg\" />\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n[button link=\"http://google.com\" color=\"black\"]Learn More[/button]",
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
	                    )
					)
				),
				'featured_below' => array()
			),
			'business_2' => array(
				'name' => 'Business Homepage #2',
				'id' => 'business_2',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-business_2.png',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					)
				),
				'primary' => array(
					'element_2' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
	                        'button' => 1,
	                        'button_text' => 'Get Started Today!',
	                        'button_color' => 'default',
	                        'button_url' => 'http://www.google.com',
	                        'button_target' => '_blank'
						)
					),
					'element_3' => array(
	                    'type' => 'columns',
	                    'query_type' => 'none',
	                    'options' => array(
	                        'setup' => array(
								'num' => '4',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_4-grid_4-grid_4',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="clock" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="pie_chart" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="coffee_mug" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="computer" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
	                    )
					)
				),
				'featured_below' => array()
			),
			'business_3' => array(
				'name' => 'Business Homepage #3',
				'id' => 'business_3',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-business_3.png',
				'sidebar_layout' => 'sidebar_right',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					),
					'element_2' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
	                        'button' => 1,
	                        'button_text' => 'Get Started Today!',
	                        'button_color' => 'default',
	                        'button_url' => 'http://www.google.com',
	                        'button_target' => '_blank'
						)
					)
				),
				'primary' => array(
					'element_3' => array(
						'type' => 'content',
						'query_type' => 'none',
						'options' => array(
							'source' => 'raw',
							'page_id' => null,
							'raw_content' => "<h2>Welcome to our fancy-schmancy website.</h2>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n\n<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>\n\n<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>\n\n[one_half]\n<h4>We Rock</h4>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n\n[/one_half]\n[one_half last]\n<h4>Hire Us</h4>\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n[/one_half]",
							'raw_format' => 0
						)
					),
				),
				'featured_below' => array()
			),
			'business_4' => array(
				'name' => 'Business Homepage #4',
				'id' => 'business_4',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-business_4.png',
				'sidebar_layout' => 'full_width',
				'featured' => array(),
				'primary' => array(
					'element_2' => array(
						'type' => 'headline',
						'query_type' => 'none',
						'options' => array(
							'text' => 'Welcome to our website',
							'tagline' => '', 
							'tag' => 'h1',
							'align' => 'left'
						)
					),
					'element_3' => array(
						'type' => 'columns',
	                    'query_type' => 'none',
	                    'options' => array(
	                        'setup' => array(
								'num' => '3',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_6-grid_3-grid_3', // => 50% | 25% | 25%
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<img src=\"$imgpath/business_4.jpg\" class=\"pretty\" />\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in bibendum enim. Nunc in est vitae leo imperdiet suscipit et sagittis leo. Nullam consectetur placerat sem, vitae feugiat lorem posuere nec. Etiam et magna nunc, et faucibus elit. Integer vitae pretium sem. Duis vitae lorem magna, ac tincidunt dolor. Phasellus justo metus, luctus in hendrerit eu, mattis eget lacus. Donec nulla turpis, euismod aliquam aliquam sed, semper vitae enim. Sed venenatis ligula eu enim tempor eget imperdiet dui pulvinar. Etiam et magna nunc, et faucibus elit. Integer vitae pretium sem.",
								'raw_format' => 1
							),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "[icon image=\"clock\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat\n\n[icon image=\"pie_chart\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "[icon image=\"coffee_mug\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n[icon image=\"computer\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
	                    )
					),
					'element_4' => array(
						'type' => 'post_grid_slider',
						'query_type' => 'secondary',
						'options' => array(
							'fx' => 'slide',
							'timeout' => 0,
							'nav_standard' => 1,
							'nav_arrows' => 1,
							'pause_play' => 1,
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 1,
							'numberposts' => -1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0
						)
					)
				),
				'featured_below' => array()
			),
			'magazine_1' => array(
				'name' => 'Classic Magazine #1',
				'id' => 'magazine_1',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-magazine_1.png',
				'sidebar_layout' => 'sidebar_right',
				'featured' => array(),
				'primary' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					),
					'element_2' => array(
						'type' => 'post_grid_paginated',
						'query_type' => 'primary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 2,
							'rows' => 3,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0
						)
					)
				),
				'featured_below' => array()
			),
			'magazine_2' => array(
				'name' => 'Classic Magazine #2',
				'id' => 'magazine_2',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-magazine_2.png',
				'sidebar_layout' => 'sidebar_right',
				'featured' => array(),
				'primary' => array(
					'element_1' => array(
						// 1 post featured above everything else
						'type' => 'post_list',
						'query_type' => 'secondary',
						'options' => array(
							'categories' => array('all'=>1),
							'thumbs' => 'full',
							'content' => 'default',
							'numberposts' => 1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0,
							'link' => 0,
							'link_text' => 'View All Posts',
							'link_url' => 'http://www.your-site.com/your-blog-page',
							'link_target' => '_self'
						)
					),
					'element_2' => array(
						// Continue post with offset = 1
						'type' => 'post_grid',
						'query_type' => 'secondary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 3,
							'rows' => 3,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 1,
							'link' => 0,
							'link_text' => 'View All Posts &rarr;',
							'link_url' => 'http://www.your-site.com/your-blog-page',
							'link_target' => '_self'
						)
					)
				),
				'featured_below' => array()
			),
			'agency' => array(
				'name' => 'Design Agency',
				'id' => 'agency',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-agency.png',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.',
							'button' => 0,
							'button_text' => 'Get Started Today!',
							'button_color' => 'default',
							'button_url' => 'http://www.your-site.com/your-landing-page',
							'button_target' => '_self'
						)
					),
					'element_2' => array(
						'type' => 'post_grid_slider',
						'query_type' => 'secondary',
						'options' => array(
							'fx' => 'slide',
							'timeout' => 0,
							'nav_standard' => 1,
							'nav_arrows' => 1,
							'pause_play' => 1,
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 2,
							'numberposts' => -1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0
						)
					)
				),
				'primary' => array(
					'element_3' => array(
						'type' => 'columns',
						'query_type' => 'none',
						'options' => array(
	                        'setup' => array(
								'num' => '3',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_4-grid_4-grid_4',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"clock\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"computer\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"pie_chart\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"image\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"coffee_mug\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n<h3>Lorem ipsum dolor sit</h3>\n\n[icon image=\"camera\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
	                    )
					)
				),
				'featured_below' => array()
			),
			'portfolio' => array(
				'name' => 'Portfolio Homepage',
				'id' => 'portfolio',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-portfolio.png',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					)
				),
				'primary' => array(
					'element_2' => array(
						'type' => 'post_grid_paginated',
						'query_type' => 'primary',
						'options' => array(
							'type' => 'post_grid_paginated',
							'query_type' => 'primary',
							'options' => array(
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 3,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0
						)
						)
					)
				),
				'featured_below' => array()
			),
			'showcase' => array(
				'name' => 'Showcase Blogger',
				'id' => 'showcase',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-showcase.png',
				'sidebar_layout' => 'sidebar_right',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					),
					'element_2' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
	                        'button' => 1,
	                        'button_text' => 'Get Started Today!',
	                        'button_color' => 'default',
	                        'button_url' => 'http://www.google.com',
	                        'button_target' => '_blank'
						)
					)
				),
				'primary' => array(
					'element_3' => array(
						'type' => 'post_list_paginated',
						'query_type' => 'primary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 3,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0
						)
					)
				),
				'featured_below' => array()
			),
			'orman' => array(
				'name' => 'The Orman',
				'id' => 'orman',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-orman.png',
				'credit' => 'Inspired by Orman Clark\'s <a href="http://themeforest.net/item/classica-minimalistic-wordpress-portfolio-theme/155672" target="_blank">Classica</a>',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.',
							'button' => 0,
							'button_text' => 'Get Started Today!',
							'button_color' => 'default',
							'button_url' => 'http://www.your-site.com/your-landing-page',
							'button_target' => '_self'
						)
					),
					'element_2' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					)
				),
				'primary' => array(
					'element_3' => array(
						'type' => 'headline',
						'query_type' => 'none',
						'options' => array(
							'text' => 'From the Portfolio',
							'tagline' => 'Check out our latest work.',
							'tag' => 'h3',
							'align' => 'left'
						)
					),
					'element_4' => array(
						'type' => 'post_grid',
						'query_type' => 'secondary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0,
							'link' => 1,
							'link_text' => 'Go to the Portfolio &rarr;',
							'link_url' => 'http://www.your-site.com/your-blog-page',
							'link_target' => '_self'
						)
					),
					'element_5' => array(
						'type' => 'headline',
						'query_type' => 'none',
						'options' => array(
							'text' => 'From the Blog',
							'tagline' => 'See what we\'re talking about.',
							'tag' => 'h3',
							'align' => 'left'
						)
					),
					'element_6' => array(
						'type' => 'post_grid',
						'query_type' => 'secondary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0,
							'link' => 1,
							'link_text' => 'Go to the Blog &rarr;',
							'link_url' => 'http://www.your-site.com/your-blog-page',
							'link_target' => '_self'
						)
					)
				),
				'featured_below' => array()
			),
			'mcalister' => array(
				'name' => 'The McAlister',
				'id' => 'mcalister',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-mcalister.png',
				'credit' => 'Inspired by Mike McAlister\'s <a href="http://themeforest.net/item/transfer-wordpress-theme/505769" target="_blank">Transfer</a>',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'post_grid',
						'query_type' => 'secondary',
						'options' => array(
							'categories' => array('all'=>1),
							'columns' => 4,
							'rows' => 1,
							'orderby' => 'post_date',
							'order' => 'DESC',
							'offset' => 0,
							'link' => 0,
							'link_text' => 'View All Posts',
							'link_url' => 'http://www.your-site.com/your-blog-page',
							'link_target' => '_self'
						)
					)
				),
				'primary' => array(
					'element_2' => array(
						'type' => 'columns',
						'query_type' => 'none',
						'options' => array(
							'setup' => array(
								'num' => '3',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_6-grid_3-grid_3',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h2>Lorem ipsum dolor sit amet</h2>\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
								'raw_format' => 1
	                        ),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h4>Lorem ipsum dolor</h4>\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.",
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<h4>Lorem ipsum dolor</h4>\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.",
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
						)
					),
					'element_3' => array(
						'type' => 'columns',
						'query_type' => 'none',
						'options' => array(
							'setup' => array(
								'num' => '3',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_3-grid_3-grid_6',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "[icon image=\"clock\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat\n\n[icon image=\"pie_chart\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
	                        	'raw_format' => 1
	                        ),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "[icon image=\"coffee_mug\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\n[icon image=\"computer\" align=\"left\"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => "<img src=\"$imgpath/business_4.jpg\" class=\"pretty\" />\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in bibendum enim. Nunc in est vitae leo imperdiet suscipit et sagittis leo. Nullam consectetur placerat sem, vitae feugiat lorem posuere nec. Etiam et magna nunc, et faucibus elit. Integer vitae pretium sem. Duis vitae lorem magna, ac tincidunt dolor. Phasellus justo metus, luctus in hendrerit eu, mattis eget lacus. Donec nulla turpis, euismod aliquam aliquam sed, semper vitae enim. Sed venenatis ligula eu enim tempor eget imperdiet dui pulvinar. Etiam et magna nunc, et faucibus elit. Integer vitae pretium sem.",
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
						)
						
					),
					'element_4' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.',
							'button' => 0,
							'button_text' => 'Get Started Today!',
							'button_color' => 'default',
							'button_url' => 'http://www.your-site.com/your-landing-page',
							'button_target' => '_self'
						)
					)
				),
				'featured_below' => array()
			),
			'webtreats' => array(
				'name' => 'The WebTreats Special',
				'id' => 'webtreats',
				'preview' => BUILDER_FRAMEWORK_DIRECTORY . 'images/sample-webtreats.png',
				'credit' => 'Inspired by WebTreats\' <a href="http://themeforest.net/item/awake-powerful-professional-wordpress-theme/111267" target="_blank">Awake</a>',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					),
					'element_2' => array(
						'type' => 'slogan',
						'query_type' => 'none',
						'options' => array(
							'slogan' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
	                        'button' => 1,
	                        'button_text' => 'Get Started Today!',
	                        'button_color' => 'default',
	                        'button_url' => 'http://www.google.com',
	                        'button_target' => '_blank'
						)
					)
				),
				'primary' => array(
					'element_3' => array(
						'type' => 'columns',
						'query_type' => 'none',
						'options' => array(
	                        'setup' => array(
								'num' => '4',
								'width' => array(
									'2' => 'grid_6-grid_6',
									'3' => 'grid_4-grid_4-grid_4',
									'4' => 'grid_3-grid_3-grid_3-grid_3',
									'5' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1'
								)
							),
	                        'col_1' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="clock" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_2' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="pie_chart" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_3' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="coffee_mug" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_4' => array(
								'type' => 'raw',
								'page' => null,
								'raw' => '[icon image="computer" align="left"]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
								'raw_format' => 1
							),
	                        'col_5' => array(
								'type' => null,
								'page' => null,
								'raw' => null,
								'raw_format' => 1
							)
	                    )
					),
					'element_4' => array(
						'type' => 'tweet',
						'query_type' => 'none',
						'options' => array(
							'account' => 'themeblvd',
							'icon' => 'twitter'
						)
					)
				),
				'featured_below' => array()
			)
		);
		
		// Apply any user-created sample layouts
		$samples = array_merge( $samples, $_themeblvd_user_sample_layouts );
		
		// Remove sample layouts
		if( $_themeblvd_remove_sample_layouts )
			foreach( $_themeblvd_remove_sample_layouts as $layout )
				unset( $samples[$layout] );
		
		// Return with filters applied
		return apply_filters( 'themeblvd_sample_layouts', $samples );
	}	
}