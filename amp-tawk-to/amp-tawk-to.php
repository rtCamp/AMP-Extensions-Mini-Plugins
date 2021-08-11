<?php
/**
 * AMP plugin to add tawk.to chat
 *
 * @package   Google\AMP_Tawk_To_Compat
 * @author    milindmore22
 * @license   GPL-2.0-or-later
 * @copyright 2021 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Tawk.to Chat
 * Plugin URI: https://wpindia.co.in/
 * Description: The WordPress plugin to add live chat / Chat bot to AMP endpoint with <a href="https://www.tawk.to/">tawk.to</a>
 * Version: 0.1
 * Author: milindmore22, rtCamp, Google
 * Author URI: https://wpindia.co.in/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Google\AMP_Tawk_To_Compat;

const OPTION_NAME         = 'tawkto_direct_chat_link';
const AMP_TAWK_TO_VERSION = '0.1';
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
	 *  Adding only at AMP endpoint.
	 */
	if ( is_amp() ) {

		/**
		 * The Action will override the scripts and styles.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
		 */
		add_action( 'wp_head', __NAMESPACE__ . '\override_scripts_and_styles' );
		add_action( 'amp_post_template_css', __NAMESPACE__ . '\override_scripts_and_styles' );

		add_action( 'wp_footer', __NAMESPACE__ . '\add_tawkto_chat_button' );
		add_action( 'amp_post_template_footer', __NAMESPACE__ . '\add_tawkto_chat_button' );
		add_action( 'wp_footer', __NAMESPACE__ . '\add_tawkto_chat_box' );
		add_action( 'amp_post_template_footer', __NAMESPACE__ . '\add_tawkto_chat_box' );
	}
}

add_action( 'wp', __NAMESPACE__ . '\add_hooks' );

/**
 * Remove enqueued JS.
 *
 * @see lovecraft_load_javascript_files()
 */
function override_scripts_and_styles() {

	$amp_tawkto_style = file_get_contents( plugin_dir_url( __FILE__ ) . '/css/amp-style.css' );

	if ( function_exists( 'amp_is_legacy' ) && amp_is_legacy() ) {

		echo $amp_tawkto_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	} else {
		?>
		<style type="text/css">
			<?php echo $amp_tawkto_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</style>
		<?php
	}

	/**
	 * Adds your custom inline style.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_add_inline_style/
	 */
	wp_enqueue_style( 'amp_tawk_to_style', plugin_dir_url( __FILE__ ) . '/css/amp-style.css', '', AMP_TAWK_TO_VERSION );
}

/**
 * Add Tawkto chat box.
 */
function add_tawkto_chat_box() {
	$direct_chat_link = get_direct_chat_link();

	if ( empty( $direct_chat_link ) ) {
		return;
	}

	?>
	<div class="tawkto-box-container">
		<amp-lightbox id="tawkto-lightbox" layout="nodisplay">
			<div class="tawkto-lightbox" on="tap:tawkto-lightbox.close" role="button" tabindex="0">
				<amp-iframe layout="fixed" frameborder="0" sandbox="allow-scripts allow-same-origin" src="<?php echo esc_url( $direct_chat_link ); ?>" width="400" height="600">
				</amp-iframe>
			</div>
		</amp-lightbox>
	</div>
	<?php
}

/**
 * Add Tawkto chat button.
 */
function add_tawkto_chat_button() {
	$direct_chat_link = get_direct_chat_link();

	if ( empty( $direct_chat_link ) ) {
		return;
	}

	?>
	<amp-position-observer on="scroll:fadeTransition.seekTo(percent=event.percent)" intersection-ratios="0" layout="nodisplay">
	</amp-position-observer>
	<div class="tawkto-button-container">
		<div on="tap:tawkto-lightbox" role="button" tabindex="0" >
			<div class="tawk-button" tabindex="0">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" role="img" class="tawk-min-chat-icon">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M400 26.2c-193.3 0-350 156.7-350 350 0 136.2 77.9 254.3 191.5 312.1 15.4 8.1 31.4 15.1 48.1 20.8l-16.5 63.5c-2 7.8 5.4 14.7 13 12.1l229.8-77.6c14.6-5.3 28.8-11.6 42.4-18.7C672 630.6 750 512.5 750 376.2c0-193.3-156.7-350-350-350zm211.1 510.7c-10.8 26.5-41.9 77.2-121.5 77.2-79.9 0-110.9-51-121.6-77.4-2.8-6.8 5-13.4 13.8-11.8 76.2 13.7 147.7 13 215.3.3 8.9-1.8 16.8 4.8 14 11.7z"></path>
				</svg>
			</div>
		</div>
	</div>
	<amp-animation id="fadeTransition" layout="nodisplay">
		<script type="application/json">
			{
				"duration": "1",
				"fill": "both",
				"direction": "reverse",
				"animations": [
					{
						"selector": ".tawkto-button-container",
						"keyframes": [
								{ "opacity": "1", "offset": 0, "visibility":"visible" },
								{ "opacity": "0.8", "offset": 0.4, "visibility":"visible" },
								{ "opacity": "0.6", "offset": 0.5, "visibility":"visible" },
								{ "opacity": "0", "offset": 0.6, "visibility":"hidden" },
								{ "opacity": "0", "offset": 1, "visibility":"hidden" }
						]
					}
				]
			}
		</script>
	</amp-animation>
	<?php
}

/**
 * Get direct Chat Link.
 *
 * @return string ID.
 */
function get_direct_chat_link() {
	return get_option( OPTION_NAME, '' );
}

/**
 * Filter plugin action links to add settings.
 *
 * @param string[] $action_links Action links.
 * @return string[] Action links.
 */
function filter_plugin_action_links( $action_links ) {
	$action_links['settings'] = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'options-reading.php' ) . '#' . OPTION_NAME ),
		esc_html__( 'Settings', '' )
	);
	return $action_links;
}
add_filter( 'plugin_action_links_' . str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ ), __NAMESPACE__ . '\filter_plugin_action_links' );

/**
 * Register setting.
 */
function register_setting() {
	\register_setting(
		'reading',
		OPTION_NAME,
		[
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
			'description'       => esc_html__( 'tawk.to AMP settings', 'amp-auto-ads' ),
		]
	);

	add_settings_field(
		OPTION_NAME,
		esc_html__( 'tawk.to Direct Chat Link', 'amp-auto-ads' ),
		function () {
			printf(
				'<p><input id="%s" name="%s" value="%s" size="50"></p><p class="description">%s</p>',
				esc_attr( OPTION_NAME ),
				esc_attr( OPTION_NAME ),
				esc_attr( get_direct_chat_link() ),
				sprintf( '%s (<a href="%s">%s</a>)', esc_html__( 'You can find the direct chat link in tawk.to dashboard -> Add On -> Chat Widget -> Direct Chat Link', '' ), esc_url( 'https://prnt.sc/1mbdwni' ), esc_html__( 'screenshot', '' ) )
			);
		},
		'reading',
		'default',
		[
			'label_for' => OPTION_NAME,
		]
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\register_setting' );
