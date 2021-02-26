<?php
/**
 * AMP IndieWeb Plugin and theme compatibility plugin bootstrap.
 *
 * @package   Google\AMP_Indie_Web_Compat
 * @author    Milind, Google, rtCamp
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Indie Compat
 * Plugin URI: https://wpindia.co.in/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to <a href="https://wordpress.org/plugins/indieweb/">IndieWeb</a>, <a href="https://wordpress.org/plugins/webmention/">WebMentions</a>, <a href="https://wordpress.org/plugins/semantic-linkbacks/">Semantic Linkbanks</a> and <a href="https://wordpress.org/themes/sempress/">SemPress</a> theme.
 * Version: 0.1
 * Author: Milind More
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Indie_Web_Compat;

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

	if ( ! is_amp() ) {
		return;
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( 'sempress' === get_template() ) {

		/**
		 * The Action will override the scripts and styles.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
		 */
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_sempress_scripts_and_styles', 11 );

	}

	if ( \is_plugin_active( 'semantic-linkbacks/semantic-linkbacks.php' ) ) {
		/**
		 * The Action will override the scripts and styles.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
		 */
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_semantic_linkbacks_scripts_and_styles', 11 );

		/**
		 * Add sanitizers to convert non-AMP functions to AMP components.
		 *
		 * @see https://amp-wp.org/reference/hook/amp_content_sanitizers/
		 */
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );
	}

	if ( \is_plugin_active( 'indieweb-post-kinds/indieweb-post-kinds.php' ) ) {
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_indieweb_post_kinds_scripts_and_styles', 11 );
	}

}

add_action( 'wp', __NAMESPACE__ . '\add_hooks' );

/**
 * Removes enqueued JS files.
 */
function override_indieweb_post_kinds_scripts_and_styles() {
	/**
	 * If you are unable to remove any scripts by remove action, you can dequeue them here.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_dequeue_script/
	 */
	wp_dequeue_script( 'media-fragment' );
}

/**
 * Remove enqueued JS.
 *
 * @see sempress_enqueue_scripts()
 */
function override_sempress_scripts_and_styles() {
	/**
	 * If you are unable to remove any scripts by remove action, you can dequeue them here.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_dequeue_script/
	 */
	wp_dequeue_script( 'sempress-script' );

	/**
	 * Adds your custom inline style.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_add_inline_style/
	 */
	wp_add_inline_style( 'plugin_name_style', file_get_contents( __DIR__ . '/css/amp-style.css' ) );
}

/**
 * Remove enqueued JS.
 *
 * @see Semantic_Linkbacks_Plugin::enqueue_scripts()
 */
function override_semantic_linkbacks_scripts_and_styles() {
	/**
	 * If you are unable to remove any scripts by remove action, you can dequeue them here.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_dequeue_script/
	 */
	wp_dequeue_script( 'semantic-linkbacks' );
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
