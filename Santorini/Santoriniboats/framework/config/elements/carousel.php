<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

// Get params from Grid config and exclude unneeded
$grid_params = us_config( 'elements/grid.params' );
foreach( $grid_params as $grid_param_name => $grid_param ) {

	if ( strpos( $grid_param_name, 'pagination' ) !== FALSE ) { // exclude Pagination options
		unset( $grid_params[ $grid_param_name ] );
	} elseif ( strpos( $grid_param_name, 'filter' ) !== FALSE ) { // exclude Filter options
		unset( $grid_params[ $grid_param_name ] );
	} elseif ( strpos( $grid_param_name, 'breakpoint' ) !== FALSE ) { // exclude Responsive options
		unset( $grid_params[ $grid_param_name ] );
	} elseif ( in_array( $grid_param_name, array_keys( $design_options ) ) ) { // exclude Design options for correct params order
		unset( $grid_params[ $grid_param_name ] );
	}
}
unset( $grid_params['type'] ); // exclude "Display as" option

return array(
	'title' => __( 'Carousel', 'us' ),
	'description' => __( 'List of images, posts, pages or any custom post types', 'us' ),
	'icon' => 'fas fa-laptop-code',
	'params' => array_merge( $grid_params, array(

		// Carousel options
		'carousel_arrows' => array(
			'type' => 'switch',
			'switch_text' => __( 'Prev/Next arrows', 'us' ),
			'std' => FALSE,
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_arrows_style' => array(
			'title' => __( 'Arrows Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'circle' => __( 'Circles', 'us' ),
				'block' => __( 'Full height blocks', 'us' ),
			),
			'std' => 'circle',
			'cols' => 2,
			'show_if' => array( 'carousel_arrows', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_arrows_pos' => array(
			'title' => __( 'Arrows Position', 'us' ),
			'type' => 'select',
			'options' => array(
				'outside' => __( 'Outside', 'us' ),
				'inside' => __( 'Inside', 'us' ),
			),
			'std' => 'outside',
			'cols' => 2,
			'show_if' => array( 'carousel_arrows', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_arrows_size' => array(
			'title' => __( 'Arrows Size', 'us' ),
			'description' => __( 'Examples:', 'us' ) . ' <span class="usof-example">26px</span>, <span class="usof-example">3rem</span>',
			'type' => 'text',
			'std' => '1.8rem',
			'cols' => 2,
			'show_if' => array( 'carousel_arrows', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_arrows_offset' => array(
			'title' => __( 'Arrows Offset', 'us' ),
			'description' => __( 'Examples:', 'us' ) . ' <span class="usof-example">20px</span>, <span class="usof-example">2rem</span>',
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'show_if' => array( 'carousel_arrows', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_dots' => array(
			'type' => 'switch',
			'switch_text' => __( 'Navigation Dots', 'us' ),
			'std' => FALSE,
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_center' => array(
			'type' => 'switch',
			'switch_text' => __( 'First item in the center', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_slideby' => array(
			'type' => 'switch',
			'switch_text' => __( 'Slide by several items instead of one', 'us' ),
			'std' => FALSE,
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_autoheight' => array(
			'type' => 'switch',
			'switch_text' => __( 'Auto height (for 1 column only)', 'us' ),
			'std' => FALSE,
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Auto Rotation', 'us' ),
			'std' => FALSE,
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_interval' => array(
			'title' => __( 'Auto Rotation Interval (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '3',
			'show_if' => array( 'carousel_autoplay', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_autoplay_smooth' => array(
			'type' => 'switch',
			'switch_text' => __( 'Continual Rotation', 'us' ),
			'std' => FALSE,
			'classes' => 'for_above',
			'show_if' => array( 'carousel_autoplay', '!=', FALSE ),
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_speed' => array(
			'title' => __( 'Slide Speed (in milliseconds)', 'us' ),
			'type' => 'text',
			'std' => '250',
			'group' => __( 'Carousel', 'us' ),
		),
		'carousel_transition' => array(
			'title' => __( 'Transition Effect', 'us' ),
			'description' => '<a href="http://cubic-bezier.com/" target="_blank">' . __( 'Use timing function', 'us' ) . '</a>' . '. ' . __( 'Examples:', 'us' ) . ' <span class="usof-example">linear</span>, <span class="usof-example">cubic-bezier(0,1,.8,1)</span>, <span class="usof-example">cubic-bezier(.78,.13,.15,.86)</span>',
			'type' => 'text',
			'std' => '',
			'group' => __( 'Carousel', 'us' ),
		),

		// Responsive
		'breakpoint_1_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '1200px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_1_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => $misc['column_values'],
			'std' => '3',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_1_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Auto Rotation', 'us' ),
			'std' => TRUE,
			'classes' => 'for_above',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '900px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => $misc['column_values'],
			'std' => '2',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Auto Rotation', 'us' ),
			'std' => TRUE,
			'classes' => 'for_above',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '600px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => $misc['column_values'],
			'std' => '1',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Auto Rotation', 'us' ),
			'std' => TRUE,
			'classes' => 'for_above',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),

	), $design_options ),
);
