<?php
/**
 * AMP Wistia Embed.
 *
 * @package   AMP_Wistia_Embed
 * @author    Milind, rtCamp
 * @license   GPL-2.0-or-later
 * @copyright 2020 rtCamp pvt. ltd.
 *
 * @wordpress-plugin
 * Plugin Name: AMP Wistia Embed Compat
 * Description: Adds AMP compatibility for Wistia Embed.
 * Version: 0.0.1
 * Author: Milind, rtCamp
 * Author URI: https://milindmore.wordpress.com/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add basic oembed support.
 */
wp_oembed_add_provider(
	'/https?\:\/\/(.+)?(wistia\.com|wi\.st|wistia\.net)\/.*/',
	'https://fast.wistia.com/oembed',
	true
);

/*
 * Filter oEmbed response for rendering videos on AMP pages.
 */
add_filter(
	'embed_oembed_html',
	function ( $cache, $url ) {
		$host = wp_parse_url( $url, PHP_URL_HOST );
		if ( ! preg_match( '/\bwistia\.com$/', $host ) || ! function_exists( 'is_amp_endpoint' ) || ! is_amp_endpoint() ) {
			return $cache;
		}

		if ( ! preg_match( '/https?:\/\/[^.]+\.(wistia\.com|wi\.st)\/(medias|embed)\/(.*?)\?/', $url, $matches ) ) {
			return $cache;
		}

		$data_media_hashed_id = $matches[3];
		$url_components       = wp_parse_url( $url );
		wp_parse_str( $url_components['query'], $url_params );

		$width  = '700';
		$height = '394';
		$layout = 'fixed';
		if ( ! empty( $url_params['videoWidth'] ) && empty( $url_params['videoFoam'] ) ) {
			$width = $url_params['videoWidth'];
			// Added 16:9 asepct ratio height.
			$height = round( $url_params['videoWidth'] / 1.77777777778 );
		}

		if ( ! empty( $url_params['videoFoam'] ) ) {
			$layout = 'responsive';
		}

		$cache = sprintf(
			'<amp-wistia-player data-media-hashed-id="%1$s" width="%2$s" height="%3$s" layout="%4$s"></amp-wistia-player>',
			esc_attr( $data_media_hashed_id ),
			esc_attr( $width ),
			esc_attr( $height ),
			esc_attr( $layout )
		);

		return $cache;
	},
	10,
	2
);
