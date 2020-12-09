<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Chaplin_Theme_Compat
 */

namespace Google\AMP_Colormag_Theme_Compat;

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

		// Set up nav menu.
		$menu_toggle    = $xpath->query( '//p[ @class = "menu-toggle" ]' )->item( 0 );
		$menu_container = $xpath->query( '//nav[ @id = "site-navigation" ]' )->item( 0 );
		$mobile_menu    = $xpath->query( '//ul[ @id = "menu-mobile-menu" ]' )->item( 0 );

		if ( $menu_toggle instanceof DOMElement ) {

			$menu_toggle->parentNode->insertBefore(
				$this->create_amp_state( 'MenuActive', false ),
				$menu_toggle
			);

			$menu_toggle->setAttribute( 'role', 'button' );
			$menu_toggle->setAttribute( 'tabindex', '1' );
			$menu_toggle->setAttribute( 'on', 'tap:AMP.setState( { MenuActive: ! MenuActive, searchActive: false } )' );
			$menu_toggle->setAttribute(
				'data-amp-bind-aria-expanded',
				sprintf( '( MenuActive ? "true" : "false" )' )
			);

			$mobile_menu->setAttribute(
				'data-amp-bind-aria-expanded',
				sprintf( '( MenuActive ? "true" : "false" )' )
			);

			$menu_container->setAttribute(
				'data-amp-bind-class',
				sprintf( '( MenuActive ? "main-small-navigation clearfix" : "main-navigation clearfix" )' )
			);
		}

		// Search in Menu toggle.
		$search_toggle = $xpath->query( '//i[contains(@class,"search-top")]' )->item( 0 );
		$search_modal  = $xpath->query( '//div[ @class = "search-form-top" ]' )->item( 0 );

		if ( $search_toggle instanceof DOMElement && $search_modal instanceof DOMElement ) {
			$search_toggle->parentNode->insertBefore(
				$this->create_amp_state( 'searchActive', false ),
				$search_toggle
			);

			$search_toggle->setAttribute( 'role', 'button' );
			$search_toggle->setAttribute( 'tabindex', '2' );
			$search_toggle->setAttribute( 'on', 'tap:AMP.setState( { searchActive: ! searchActive, MenuActive: false } )' );
			$menu_toggle->setAttribute( 'role', 'button' );
			$search_modal->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( searchActive ? " show" : "" )', wp_json_encode( $search_modal->getAttribute( 'class' ) ) )
			);
		}

		// Gallery BX Slider.
		$galleries = $xpath->query( '//ul[contains(@class,"gallery-images")]' );

		if ( $galleries instanceof \DOMNodeList ) {
			foreach ( $galleries as $gallery ) {
				$gallery->parentNode->replaceChild(
					$this->create_amp_carousel( $gallery ),
					$gallery
				);
			}
		}

	}

	/**
	 * Added BX Slider Support.
	 *
	 * @param DOMElement $gallery Gallery Element.
	 * @return DOMElement AMP Carousel.
	 */
	private function create_amp_carousel( $gallery ) {
		$amp_carousel = $this->dom->createElement( 'amp-carousel' );
		$amp_carousel->setAttribute( 'autoplay', '' );
		$amp_carousel->setAttribute( 'delay', '1500' );
		$amp_carousel->setAttribute( 'width', '400' );
		$amp_carousel->setAttribute( 'height', '300' );
		$amp_carousel->setAttribute( 'layout', 'responsive' );
		$amp_carousel->setAttribute( 'type', 'slides' );
		$amp_carousel->setAttribute( 'role', 'region' );
		$amp_carousel->setAttribute( 'aria-label', 'Carousel with custom button styles' );

		if ( $gallery->childNodes instanceof \DOMNodeList ) {
			foreach ( $gallery->childNodes as $gallery_node ) {
				if ( $gallery_node instanceof \DOMElement ) {
					$imgage    = $gallery_node->getElementsByTagName( 'img' )->item( 0 );
					$amp_image = $this->dom->createElement( 'amp-img' );
					$amp_image->setAttribute( 'src', $imgage->getAttribute( 'src' ) );
					$amp_image->setAttribute( 'width', '400' );
					$amp_image->setAttribute( 'height', '300' );
					$amp_image->setAttribute( 'layout', 'responsive' );
					$amp_image->setAttribute( 'alt', $imgage->getAttribute( 'alt' ) );
					$amp_carousel->appendChild( $amp_image );
				}
			}
		}

		return $amp_carousel;
	}

	/**
	 * Create AMP state.
	 *
	 * @param string $id    State ID.
	 * @param mixed  $value State value.
	 * @return DOMElement An amp-state element.
	 */
	private function create_amp_state( $id, $value ) {
		$amp_state = $this->dom->createElement( 'amp-state' );
		$amp_state->setAttribute( 'id', $id );
		$script = $this->dom->createElement( 'script' );
		$script->setAttribute( 'type', 'application/json' );
		$script->appendChild( $this->dom->createTextNode( wp_json_encode( $value ) ) );
		return $amp_state;
	}

}
