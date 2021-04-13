<?php
/**
 * AMP plugin name compatibility plugin bootstrap.
 *
 * @package   Google\AMP_PDF_Embedder_Compat
 * @author    milindmore22, Google
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP compatibility for PDF Embedder.
 * Plugin URI: https://wpindia.co.in/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to <a href="https://wordpress.org/plugins/pdf-embedder/">PDF Embedder</a>.
 * Version: 0.1
 * Author: milindmore22, Google
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_PDF_Embedder_Compat;

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

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	/**
	 *  Check if PDF Embedder plugin is active.
	 */
	if ( \is_plugin_active( 'pdf-embedder/pdf_embedder.php' ) && is_amp() ) {

		/**
		 * The Action will override the scripts and styles.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
		 */
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_scripts_and_styles', 15 );

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
 * Remove enqueued JS.
 *
 * @see lovecraft_load_javascript_files()
 */
function override_scripts_and_styles() {

	/**
	 * deregister script as they are enqueue on shortcode init.
	 */
	wp_deregister_script( 'pdfemb_embed_pdf_js' );
	wp_deregister_script( 'pdfemb_pdf_js' );
}

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
