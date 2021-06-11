<?php
/**
 * AMP plugin to hide div with class
 *
 * @package   Google\AMP_Hide
 * @author    Your Name, Google
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Hide
 * Plugin URI: https://github.com/milindmore22/amp-hide
 * Description: Plugin to add feature to hide any element on <a href="https://wordpress.org/plugins/amp/">AMP</a> endpoint.
 * Version: 0.1
 * Author: Google, Weston Ruter, rtCamp, milindmore22
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Hide;

/**
 * Whether the page is AMP.
 *
 * @return bool Is AMP.
 */
function is_amp() {
	return function_exists( 'amp_is_request' ) && amp_is_request();
}

/**
 * Run Hooks.
 */
function add_hooks() {

	if ( is_amp() ) {

		/**
		 * Add sanitizers to convert non-AMP functions to AMP components.
		 *
		 * @see https://amp-wp.org/reference/hook/amp_content_sanitizers/
		 */
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );
	}
}

add_action( 'wp', __NAMESPACE__ . '\add_hooks' );

/**
 * Add sanitizer to fix up the markup.
 *
 * @param array $sanitizers Sanitizers.
 * @return array Sanitizers.
 */
function filter_sanitizers( $sanitizers ) {
	require_once __DIR__ . '/sanitizers/class-sanitizer.php';
	$sanitizers[ __NAMESPACE__ . '\Sanitizer' ] = array();
	return $sanitizers;
}
