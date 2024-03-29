<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Meta Boxes
 *
 * @filter us_config_meta-boxes
 */
 
// Get CPTs
$public_cpt = array_keys( us_get_public_cpt() );

// Get Page Blocks
$us_page_blocks_list = us_get_posts_titles_for( 'us_page_block' );

return array(

	// Page Layout
	array(
		'id' => 'us_page_settings',
		'title' => __( 'Page Layout', 'us' ),
		'post_types' => array_merge(
			array( 'post', 'page', 'us_portfolio', 'product' ), // predefined types
			$public_cpt
		),
		'context' => 'side',
		'priority' => 'low',
		'fields' => array(
		
			// Header
			'us_header_id' => array(
				'title' => _x( 'Header', 'site top area', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_header',
				'options' =>  us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Theme Options', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), us_get_posts_titles_for( 'us_header' )
				),
				'std' => '__defaults__',
				'classes' => 'us_wpml_non_default_hidden',
			),

			// Titlebar
			'us_titlebar_id' => array(
				'title' => __( 'Titlebar', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Theme Options', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
				'place_if' => us_get_option( 'enable_sidebar_titlebar', 0 ),
			),

			// Content template
			'us_content_id' => array(
				'title' => __( 'Content template', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Theme Options', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Show content as is', 'us' ) . ' &ndash;',
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),

			// Sidebar
			'us_sidebar_id' => array(
				'title' => __( 'Sidebar', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Theme Options', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), us_get_sidebars()
				),
				'std' => '__defaults__',
				'place_if' => us_get_option( 'enable_sidebar_titlebar', 0 ),
			),

			// Sidebar Position
			'us_sidebar_pos' => array(
				'type' => 'radio',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'right',
				'classes' => 'for_above',
				'show_if' => array( 'us_sidebar_id', 'not in', array( '', '__defaults__' ) ),
				'place_if' => us_get_option( 'enable_sidebar_titlebar', 0 ),
			),

			// Footer
			'us_footer_id' => array(
				'title' => __( 'Footer', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Theme Options', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),
		),
	),

	// Custom appearance in Grid
	array(
		'id' => 'us_portfolio_settings',
		'title' => __( 'Custom appearance in Grid', 'us' ),
		'post_types' => array_merge(
			array( 'post', 'page', 'us_portfolio', 'product' ), // predefined types
			$public_cpt
		),
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			'us_tile_bg_color' => array(
				'title' => __( 'Background Color', 'us' ),
				'type' => 'color',
				'classes' => 'clear_right',
			),
			'us_tile_text_color' => array(
				'title' => __( 'Text Color', 'us' ),
				'type' => 'color',
				'classes' => 'clear_right',
			),
			'us_tile_size' => array(
				'title' => __( 'Custom Size', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1x1' => us_translate( 'None' ),
					'2x1' => '2x1',
					'1x2' => '1x2',
					'2x2' => '2x2',
				),
				'std' => '1x1',
			),
			'us_tile_link' => array(
				'title' => __( 'Custom Link', 'us' ),
				'type' => 'link',
				'placeholder' => us_translate( 'Enter the URL' ),
				'std' => '',
			),
			'us_tile_additional_image' => array(
				'title' => __( 'Additional Image', 'us' ),
				'type' => 'upload',
				'extension' => 'png,jpg,jpeg,gif,svg',
			),
		),
	),

	// Testimonials settings
	array(
		'id' => 'us_testimonials_settings',
		'title' => __( 'More Options', 'us' ),
		'post_types' => array( 'us_testimonial' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			'us_testimonial_author' => array(
				'title' => __( 'Author Name', 'us' ),
				'type' => 'text',
				'std' => 'John Doe',
			),
			'us_testimonial_role' => array(
				'title' => __( 'Author Role', 'us' ),
				'type' => 'text',
				'std' => '',
			),
			'us_testimonial_company' => array(
				'title' => __( 'Author Company', 'us' ),
				'type' => 'text',
				'std' => '',
			),
			'us_testimonial_link' => array(
				'title' => __( 'Author Link', 'us' ),
				'type' => 'link',
				'placeholder' => us_translate( 'Enter the URL' ),
				'std' => '',
			),
			'us_testimonial_rating' => array(
				'title' => __( 'Rating', 'us' ),
				'type' => 'radio',
				'options' => array(
					'none' => us_translate( 'None' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'std' => 'none',
			),
		),
	),

	// Page Block info
	array(
		'id' => 'us_post_info',
		'title' => __( 'Used in', 'us' ),
		'post_types' => array( 'us_page_block' ),
		'context' => 'side',
		'priority' => 'default',
		'fields' => array(
			'used_in_locations' => array(
				'description' => '',
				'type' => 'message',
			),
		),
	),
);
