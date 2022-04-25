<?php
/**
 * AMP Facebook Comments.
 *
 * @package   AMP_FB_Comments_Compat
 * @author    Milind, rtCamp
 * @license   GPL-2.0-or-later
 * @copyright 2020 rtCamp pvt. ltd.
 *
 * @wordpress-plugin
 * Plugin Name: AMP FB Comments
 * Description: Adds AMP compatibility for Lazy Social Comments plugin.
 * Version: 0.0.1
 * Author: Milind, rtCamp
 * Author URI: https://milindmore.wordpress.com/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_FB_Comments_Compat;

defined( 'ABSPATH' ) || exit;

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

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_amp() && \is_plugin_active( 'lazy-facebook-comments/lazy-facebook-comments.php' ) ) {
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );
		add_filter( 'comments_template', __NAMESPACE__ . '\amp_compatible_facebook_comments' );
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
	require_once __DIR__ . '/class-sanitizer.php';
	$sanitizers[ __NAMESPACE__ . '\Sanitizer' ] = [];
	return $sanitizers;
}

/**
 * Loads Facebook comment form.
 *
 * @return facbook comment form.
 */
function amp_compatible_facebook_comments() {
	return __DIR__ . '/amp-compatible-form.php';
}
