<?php
/**
 * AMP compatibility for Email Subscribers plugin.
 *
 * @package   Google\AMP_Email_Subscribers_Compat
 * @author    milindmore22, Google, rtCamp, AMP
 * @license   GPL-2.0-or-later
 * @copyright 2021 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP compatibility plugin for Email Subscribers
 * Plugin URI: https://wpindia.co.in/
 * Description: Plugin to add <a href="https://wordpress.org/plugins/amp/">AMP plugin</a> compatibility to <a href="https://wordpress.org/plugins/email-subscribers/" target="_blank">Email Subscribers & Newsletters</a>.
 * Version: 0.1
 * Author: milindmore22, Google, rtCamp
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Email_Subscribers_Compat;

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
	if ( class_exists( 'Email_Subscribers' ) && is_amp() ) {
		/**
		 * The Action will override the scripts and styles.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
		 */
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\override_scripts_and_styles', 11 );

		/**
		 * Add sanitizers to convert non-AMP functions to AMP components.
		 *
		 * @see https://amp-wp.org/reference/hook/amp_content_sanitizers/
		 */
		add_filter( 'amp_content_sanitizers', __NAMESPACE__ . '\filter_sanitizers' );

		/**
		 * Add Response message for Email Subscriber form.
		 */
		add_action( 'ig_es_after_form_fields', __NAMESPACE__ . '\email_subscribers_response_messages' );

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
	 * If you are unable to remove any scripts by remove action, you can dequeue them here.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_dequeue_script/
	 */
	wp_dequeue_script( 'email-subscribers' );

	/**
	 * Adds your custom inline style.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_add_inline_style/
	 */
	//wp_add_inline_style( 'plugin_name_style', file_get_contents( __DIR__ . '/css/amp-style.css' ) );
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

/**
 * Bonus improvement: add font-display:swap to the Google Fonts!
 *
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://developers.google.com/fonts/docs/getting_started
 * @see https://developer.wordpress.org/reference/hooks/style_loader_src/
 *
 * @param string $src    Stylesheet URL.
 * @param string $handle Style handle.
 * @return string Filtered stylesheet URL.
 */
function filter_font_style_loader_src( $src, $handle ) {
	if ( 'google-font-handle' === $handle ) {
		$src = add_query_arg( 'display', 'swap', $src );
	}
	return $src;
}

//add_filter( 'style_loader_src', __NAMESPACE__ . '\filter_font_style_loader_src', 10, 2 );


/**
 * Add Mustache Messages.
 */
function email_subscribers_response_messages() {
	?>
	<div class="amp-wp-default-form-message" submit-success>
		<template type="amp-mustache">
			<p class="{{#redirecting}}amp-wp-form-redirecting{{/redirecting}}">
				Success! Thanks {{name}} for subscription! please check your email to confirm subscription.
			</p>
		</template>
	</div>
	<div class="amp-wp-default-form-message" submit-error>
		<template type="amp-mustache">
			<p class="{{#redirecting}}amp-wp-form-redirecting{{/redirecting}}">
				Error! Thanks {{name}} for trying to subscribe but there was an error!.
			</p>
		</template>
	</div>
	<div class="amp-wp-default-form-message" submitting="">
		<template type="amp-mustache"><p>Submitting…</p></template>
	</div>
	<?php
}
