<?php
/**
 * AMP plugin name compatibility plugin bootstrap.
 *
 * @package   Google\AMP_Infinite_Scroll
 * @author    Your Name, Google
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Infinite Scroll
 * Plugin URI: https://wpindia.co.in/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> to add infinite scroll.
 * Version: 0.1
 * Author: milindmore22, Google
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Infinite_Scroll;

/**
 * Whether the page is AMP.
 *
 * @return bool Is AMP.
 */
function is_amp() {
	return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
}

/**
 * Run Hooks.
 */
function add_hooks() {

	/**
	 *  Keep this if you are using theme.
	 */
	if ( is_amp() && ( is_home() || is_archive() || is_search() ) ) {
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
