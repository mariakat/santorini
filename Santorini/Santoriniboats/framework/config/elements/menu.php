<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => us_translate( 'Menu' ),
	'icon' => 'fas fa-bars',
	'params' => array_merge( array(
		'source' => array(
			'title' => us_translate( 'Menu' ),
			'description' => $misc['desc_menu_select'],
			'type' => 'select',
			'options' => us_get_nav_menus(),
			'std' => 'header-menu',
		),
		'font' => array(
			'title' => __( 'Font', 'us' ),
			'type' => 'select',
			'options' => us_get_fonts(),
			'std' => 'body',
		),
		'font_weight' => array(
			'title' => __( 'Font Weight', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'100' => '100 ' . __( 'thin', 'us' ),
				'200' => '200 ' . __( 'extra-light', 'us' ),
				'300' => '300 ' . __( 'light', 'us' ),
				'400' => '400 ' . __( 'normal', 'us' ),
				'500' => '500 ' . __( 'medium', 'us' ),
				'600' => '600 ' . __( 'semi-bold', 'us' ),
				'700' => '700 ' . __( 'bold', 'us' ),
				'800' => '800 ' . __( 'extra-bold', 'us' ),
				'900' => '900 ' . __( 'ultra-bold', 'us' ),
			),
			'std' => '',
			'cols' => 3,
		),
		'text_transform' => array(
			'title' => __( 'Text Transform', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'none' => us_translate( 'None' ),
				'uppercase' => 'UPPERCASE',
				'lowercase' => 'lowercase',
				'capitalize' => 'Capitalize',
			),
			'std' => '',
			'cols' => 3,
		),
		'font_style' => array(
			'title' => __( 'Font Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'normal' => __( 'normal', 'us' ),
				'italic' => __( 'italic', 'us' ),
			),
			'std' => '',
			'cols' => 3,
		),
		'font_size' => array(
			'title' => __( 'Main Items Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '1rem',
			'cols' => 2,
		),
		'indents' => array(
			'title' => __( 'Distance Between Main Items', 'us' ),
			'type' => 'slider',
			'std' => '20px',
			'options' => array(
				'px' => array(
					'min' => 0,
					'max' => 50,
				),
				'rem' => array(
					'min' => 0.0,
					'max' => 3.0,
					'step' => 0.1,
				),
				'em' => array(
					'min' => 0.0,
					'max' => 3.0,
					'step' => 0.1,
				),
				'vw' => array(
					'min' => 0,
					'max' => 10,
				),
				'vh' => array(
					'min' => 0,
					'max' => 10,
				),
			),
			'cols' => 2,
		),
		'vstretch' => array(
			'title' => __( 'Main Items Height', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Stretch to the full available height', 'us' ),
			'std' => TRUE,
		),
		'hover_effect' => array(
			'title' => __( 'Main Items Hover Effect', 'us' ),
			'type' => 'select',
			'options' => array(
				'simple' => __( 'Simple', 'us' ),
				'underline' => us_translate( 'Underline' ),
			),
			'std' => 'simple',
		),
		'dropdown_arrow' => array(
			'title' => __( 'Dropdown Indication', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Show arrows for main items with dropdown', 'us' ),
			'std' => FALSE,
			'group' => __( 'Dropdowns', 'us' ),
		),
		'dropdown_effect' => array(
			'title' => __( 'Dropdown Effect', 'us' ),
			'type' => 'select',
			'options' => $misc['dropdown_effect_values'],
			'std' => 'height',
			'group' => __( 'Dropdowns', 'us' ),
		),
		'dropdown_font_size' => array(
			'title' => __( 'Dropdown Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '1rem',
			'group' => __( 'Dropdowns', 'us' ),
		),
		'dropdown_width' => array(
			'title' => __( 'Dropdown Width', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Limit full-width dropdowns by a menu width', 'us' ),
			'std' => FALSE,
			'group' => __( 'Dropdowns', 'us' ),
		),
		'mobile_width' => array(
			'title' => __( 'Show mobile menu when screen width is less than', 'us' ),
			'type' => 'slider',
			'std' => '900px',
			'options' => array(
				'px' => array(
					'min' => 300,
					'max' => 2000,
					'step' => 10,
				),
			),
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_layout' => array(
			'title' => __( 'Mobile Menu Layout', 'us' ),
			'type' => 'radio',
			'options' => array(
				'dropdown' => __( 'Dropdown', 'us' ),
				'panel' => __( 'Vertical Panel', 'us' ),
				'fullscreen' => __( 'Full Screen', 'us' ),
			),
			'std' => 'dropdown',
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_effect_p' => array(
			'type' => 'radio',
			'options' => array(
				'afl' => __( 'Appear From Left', 'us' ),
				'afr' => __( 'Appear From Right', 'us' ),
			),
			'std' => 'afl',
			'show_if' => array( 'mobile_layout', '=', 'panel' ),
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_effect_f' => array(
			'type' => 'radio',
			'options' => array(
				'aft' => __( 'Appear From Top', 'us' ),
				'afc' => __( 'Appear From Center', 'us' ),
				'afb' => __( 'Appear From Bottom', 'us' ),
			),
			'std' => 'aft',
			'show_if' => array( 'mobile_layout', '=', 'fullscreen' ),
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_font_size' => array(
			'title' => __( 'Main Items Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '1.1rem',
			'cols' => 2,
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_dropdown_font_size' => array(
			'title' => __( 'Dropdown Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '0.9rem',
			'cols' => 2,
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_align' => array(
			'title' => __( 'Menu Items Alignment', 'us' ),
			'type' => 'radio',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_behavior' => array(
			'title' => __( 'Dropdown Behavior', 'us' ),
			'description' => __( 'When this option is OFF, mobile menu dropdown will be shown by click on an arrow only.', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Show dropdown by click on menu item title', 'us' ),
			'std' => TRUE,
			'classes' => 'desc_2',
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_icon_size' => array(
			'title' => __( 'Icon Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_icon_size_tablets' => array(
			'title' => __( 'Icon Size on Tablets', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
			'group' => __( 'Mobile Menu', 'us' ),
		),
		'mobile_icon_size_mobiles' => array(
			'title' => __( 'Icon Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
			'group' => __( 'Mobile Menu', 'us' ),
		),

	), $design_options ),
);
