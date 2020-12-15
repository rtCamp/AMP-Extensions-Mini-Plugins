<?php
/**
 * AMP WordPress Zero Theme Compat plugin.
 *
 * @package   Google\AMP_Chaplin_Theme_Compat
 * @author    Weston Ruter, Google, milindmore22
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP WordPress Zero Spam plugin Compat.
 * Plugin URI: https://github.com/milindmore22/amp-zero-spam-compatibility/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to the <a href="https://wordpress.org/plugins/zero-spam/">WordPress Zero Spam</a> plugin by Ben Marshall.
 * Version: 0.2
 * Author: Weston Ruter, Google, milindmore22
 * Author URI: https://github.com/milindmore22/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_WP_Zero_Spam_Compat;

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
 * @see \lovecraft_html_js_class()
 */
function add_hooks() {

	if ( function_exists( 'is_plugin_active' ) && \is_plugin_active( 'zero-spam/wordpress-zero-spam.php' ) && is_amp() ) {
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_scripts_and_styles', 15 );
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );
	}

}
add_action( 'wp', __NAMESPACE__ . '\add_hooks' );

/**
 * Remove enqueued JS.
 */
function override_scripts_and_styles() {
	wp_dequeue_script( 'wpzerospam' );
	wp_dequeue_script( 'wpzerospam-integration-cf7' );
	wp_dequeue_script( 'wpzerospam-integration-buddy-press' );
	wp_dequeue_script( 'wpzerospam-integration-comments' );
	wp_dequeue_script( 'wpzerospam-integration-fluentform' );
	wp_dequeue_script( 'wpzerospam-integration-registrations' );
	wp_dequeue_script( 'wpzerospam-integration-wpforms' );
	wp_dequeue_script( 'frm_entries_footer_scripts' );
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

/**
 * Loads integrations.
 */
function load_intergrations() {
	// contact form 7 integration.
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		require_once __DIR__ . '/integrations/amp-contact-form-7-support.php';
	}

}

add_action( 'init', __NAMESPACE__ . '\load_intergrations' );
