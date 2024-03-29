<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Social Links element
 */

$classes = isset( $classes ) ? $classes : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';
$list_inline_css = $item_inline_css = '';

$classes .= ' style_' . $style;
$classes .= ' hover_' . $hover;
$classes .= ' color_' . $color;
$classes .= ' shape_' . $shape;

if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {
	$classes .= ' align_' . $align;
	$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}
	$list_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $size,
			'margin' => '-' . $gap,
		)
	);
	$item_inline_css = us_prepare_inline_css(
		array(
			'padding' => $gap,
		)
	);
}

// Output the element
$output = '<div class="w-socials' . $classes . '"' . $el_id . '>';
$output .= '<div class="w-socials-list"' . $list_inline_css . '>';

$social_links = us_config( 'social_links' );

// Decoding items in case it is shortcode
if ( ! empty( $items ) AND ! is_array( $items ) ) {
	$items = json_decode( urldecode( $items ), TRUE );
	if ( ! is_array( $items ) ) {
		$items = array();
	}
} elseif ( empty( $items ) OR ! is_array( $items ) ) {
	$items = array();
}

foreach ( $items as $index => $item ) {
	$social_title = ( isset( $social_links[ $item['type'] ] ) ) ? $social_links[ $item['type'] ] : $item['type'];
	$social_url = ( isset( $item['url'] ) ) ? $item['url'] : '';
	$social_target = $social_icon = $social_custom_bg = $social_custom_color = '';

	// Custom type
	if ( $item['type'] == 'custom' ) {
		$social_title = ( ! empty( $item['title'] ) ) ? $item['title'] : us_translate( 'Title' );
		$social_url = esc_url( $social_url );
		$social_target = ' target="_blank"';
		if ( isset( $item['icon'] ) ) {
			$social_icon = us_prepare_icon_tag( $item['icon'] );
		}
		$social_custom_bg = us_prepare_inline_css(
			array(
				'background' => $item['color'],
			)
		);
		$social_custom_color = us_prepare_inline_css(
			array(
				'color' => $item['color'],
			)
		);
	// Email type
	} elseif ( $item['type'] == 'email' ) {
		if ( filter_var( $social_url, FILTER_VALIDATE_EMAIL ) ) {
			$social_url = 'mailto:' . $social_url;
		}
	// Skype type
	} elseif ( $item['type'] == 'skype' ) {
		if ( strpos( $social_url, ':' ) === FALSE ) {
			$social_url = 'skype:' . esc_attr( $social_url );
		}
	// All others types
	} else {
		$social_url = esc_url( $social_url );
		$social_target = ' target="_blank"';
	}

	$output .= '<div class="w-socials-item ' . $item['type'] . '"' . $item_inline_css . '>';
	$output .= '<a class="w-socials-item-link"';
	$output .= ' rel="nofollow"' . $social_target;
	$output .= ' href="' . $social_url . '"';
	$output .= ' title="' . esc_attr( $social_title ) . '"';
	$output .= ' aria-label="' . esc_attr( $social_title ) . '"';
	if ( $color == 'brand' ) {
		$output .= $social_custom_color;
	}
	$output .= '>';
	$output .= '<span class="w-socials-item-link-hover"' . $social_custom_bg . '></span>';
	$output .= $social_icon;
	$output .= '</a>';
	$output .= '<div class="w-socials-item-popup"><span>' . strip_tags( $social_title ) . '</span></div>';
	$output .= '</div>';
}

$output .= '</div></div>';

echo $output;
