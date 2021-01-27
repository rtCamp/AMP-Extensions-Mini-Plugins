<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Plugin_Name_Compat
 */

namespace Google\AMP_AD_Compat;

use AMP_Base_Sanitizer;
use DOMElement;
use DOMXPath;

/**
 * Class Sanitizer
 */
class Sanitizer extends AMP_Base_Sanitizer {

	/**
	 * Sanitize.
	 */
	public function sanitize() {
		$xpath = new DOMXPath( $this->dom );

		// Set up mobile nav menu.
		$google_ads = $xpath->query( '//ins [contains( @class, "adsbygoogle" ) ]' );

		if ( $google_ads instanceof \DOMNodeList ) {
			foreach ( $google_ads as $google_ad ) {
				if ( $google_ad instanceof \DOMElement ) {
					$google_ad->parentNode->replaceChild(
						$this->create_amp_ad( $google_ad ),
						$google_ad
					);
				}
			}
		}

		// Remove google adsense scripts.
		$scripts = $xpath->query( '//script[contains(., "adsbygoogle")]' );

		if ( $scripts instanceof \DOMNodeList ) {
			foreach ( $scripts as $script ) {
				if ( $script instanceof \DOMElement ) {
					$script->parentNode->removeChild( $script );
				}
			}
		}

	}

	/**
	 * Create AMP AD Element.
	 *
	 * @param instance $google_ad Google Ad DOMElement.
	 *
	 * @return DOMElement An amp-ad element.
	 */
	private function create_amp_ad( $google_ad ) {

		$style = $google_ad->getAttribute( 'style' );

		$height    = '320';
		$width     = '320';
		$split_css = array();

		if ( ! empty( $style ) ) {
			$break_style = explode( ';', $style );
			if ( ! empty( $break_style ) ) {
				foreach ( $break_style as $break ) {
					$broken                  = explode( ':', $break );
					$split_css[ $broken[0] ] = $broken[1];
				}
			}
		}

		if ( ! empty( $split_css ) ) {
			$width  = rtrim( $split_css['width'], 'px' );
			$height = rtrim( $split_css['height'], 'px' );
		}

		$data_ad_client = $google_ad->getAttribute( 'data-ad-client' );
		$data_ad_slot   = $google_ad->getAttribute( 'data-ad-slot' );
		$class          = $google_ad->getAttribute( 'class' );

		$amp_ad = $this->dom->createElement( 'amp-ad' );
		//$amp_ad->setAttribute( 'layout', 'fixed' );
		$amp_ad->setAttribute( 'type', 'adsense' );
		$amp_ad->setAttribute( 'width', $width );
		$amp_ad->setAttribute( 'height', $height );
		$amp_ad->setAttribute( 'data-ad-client', $data_ad_client );
		$amp_ad->setAttribute( 'data-ad-slot', $data_ad_slot );
		$amp_ad->setAttribute( 'class', $class );

		return $amp_ad;
	}
}
