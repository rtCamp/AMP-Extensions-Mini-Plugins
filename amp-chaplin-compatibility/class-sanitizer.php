<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Chaplin_Theme_Compat
 */

namespace Google\AMP_Chaplin_Theme_Compat;

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
		$menu_toggle      = $xpath->query( '//a[ @class = "toggle nav-toggle" ]' )->item( 0 );
		$modal_menu       = $xpath->query( '//div[ @class = "menu-modal cover-modal" ]' )->item( 0 );
		$modal_menu_close = $xpath->query( '//a[ @class = "toggle nav-toggle nav-untoggle" ]' )->item( 0 );

		if ( $menu_toggle instanceof DOMElement && $modal_menu instanceof DOMElement && $modal_menu_close instanceof DOMElement ) {

			$menu_toggle->parentNode->insertBefore(
				$this->create_amp_state( 'MenuActive', false ),
				$menu_toggle
			);

			$menu_toggle->setAttribute( 'on', 'tap:AMP.setState( { MenuActive: ! MenuActive, searchActive: false } )' );
			$menu_toggle->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( MenuActive ? " active" : "" )', wp_json_encode( $menu_toggle->getAttribute( 'class' ) ) )
			);

			$modal_menu_close->setAttribute( 'on', 'tap:AMP.setState( { MenuActive: ! MenuActive, searchActive: false } )' );
			$modal_menu_close->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( MenuActive ? " active" : "" )', wp_json_encode( $modal_menu_close->getAttribute( 'class' ) ) )
			);

			$modal_menu->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( MenuActive ? " active show-modal" : "" )', wp_json_encode( $modal_menu->getAttribute( 'class' ) ) )
			);

		}

		// Set up search.
		$search_toggle      = $xpath->query( '//a[ @class = "toggle search-toggle" ]' )->item( 0 );
		$search_modal       = $xpath->query( '//div[ @class = "search-modal cover-modal" ]' )->item( 0 );
		$search_modal_close = $xpath->query( '//a[ @class = "toggle search-untoggle fill-children-primary"]' )->item( 0 );

		if ( $search_toggle instanceof DOMElement && $search_modal instanceof DOMElement && $search_modal_close instanceof DOMElement ) {
			$search_toggle->parentNode->insertBefore(
				$this->create_amp_state( 'searchActive', false ),
				$search_toggle
			);

			$search_toggle->setAttribute( 'on', 'tap:AMP.setState( { searchActive: ! searchActive, mobileMenuActive: false } )' );
			$search_toggle->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( searchActive ? " active" : "" )', wp_json_encode( $search_toggle->getAttribute( 'class' ) ) )
			);

			$search_modal->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( searchActive ? " show-modal active" : "" )', wp_json_encode( $search_modal->getAttribute( 'class' ) ) )
			);

			$search_modal_close->setAttribute( 'on', 'tap:AMP.setState( { searchActive: ! searchActive, mobileMenuActive: false } )' );
			$search_modal_close->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( searchActive ? " active" : "" )', wp_json_encode( $search_modal_close->getAttribute( 'class' ) ) )
			);
		}

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
