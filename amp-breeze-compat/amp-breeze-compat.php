<?php
/**
 * AMP compatibility for Breeze Cache.
 *
 * @package   Google\AMP_Breeze_Cache
 * @author    Weston Ruter, Google, milindmore22
 * @license   GPL-2.0-or-later
 * @copyright 2021 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Breeze Cache compatibility
 * Plugin URI: https://github.com/milindmore22/amp-breeze-compat/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to the <a href="https://wordpress.org/plugins/breeze/">Breeze – WordPress Cache</a> plugin.
 * Version: 0.1
 * Author: Weston Ruter, Google, milindmore22
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Disabled minification for HTML, CSS and JS.
 */
function amp_breeze_compat() {

	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		// Disable html minification.
		add_filter( 'breeze_filter_html_noptimize', '__return_true' );

		// Disbale script minification.
		add_filter( 'breeze_filter_js_noptimize', '__return_true' );

		// Disable style minification.
		add_filter( 'breeze_filter_css_noptimize', '__return_true' );
	}

}

add_action( 'wp', 'amp_breeze_compat' );
