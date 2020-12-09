<?php
/**
 * AMP ColorMag Theme Compat plugin bootstrap.
 *
 * @package   Google\AMP_Colormag_Theme_Compat
 * @author    Weston Ruter, Google, milindmore22
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP ColorMag Theme Compat
 * Plugin URI: https://github.com/milindmore22/amp-colormag-compat
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to the <a href="https://wordpress.org/themes/colormag/">ColorMag</a> theme by ThemeGrill.
 * Version: 0.1
 * Author: milindmore22
 * Author URI: https://github.com/milindmore22/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Colormag_Theme_Compat;

/**
 * Whether the page is AMP.
 *
 * @return bool Is AMP.
 */
function is_amp() {
	return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
}

/**
 * Remove JS that replaces no-js with js class.
 *
 * @see \colormag_scripts_styles_method()
 */
function add_hooks() {
	if ( 'colormag' === get_template() && is_amp() ) {
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_scripts_and_styles', 11 );
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );
	}
}
add_action( 'wp', __NAMESPACE__ . '\add_hooks' );

/**
 * Remove enqueued JS.
 *
 * @see colormag_scripts_styles_method()
 */
function override_scripts_and_styles() {
	wp_dequeue_script( 'colormag-bxslider' );
	wp_dequeue_script( 'colormag-custom' );
	wp_dequeue_script( 'colormag-navigation' );
	wp_dequeue_script( 'colormag-fitvids' );
	wp_dequeue_script( 'colormag-featured-image-popup' );
	wp_dequeue_script( 'colormag-news-ticker' );
	wp_dequeue_script( 'colormag-sticky-menu' );
	wp_dequeue_script( 'colormag-skip-link-focus-fix' );
	wp_dequeue_script( 'colormag-skip-link-focus-fix' );
	wp_add_inline_style( 'colormag_style', file_get_contents( __DIR__ . '/style.css' ) );
}

/**
 * Add sanitizer to fix up the markup.
 *
 * @param array $sanitizers Sanitizers.
 * @return array Sanitizers.
 */
function filter_sanitizers( $sanitizers ) {
	require_once __DIR__ . '/class-sanitizer.php';
	$sanitizers[ __NAMESPACE__ . '\Sanitizer' ] = [];
	return $sanitizers;
}
