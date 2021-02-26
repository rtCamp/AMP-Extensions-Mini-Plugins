<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Indie_Web_Compat
 */

namespace Google\AMP_Indie_Web_Compat;

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
		$toggle_button_show = $xpath->query( '//button[ @class = "show-additional-facepiles" ]' )->item( 0 );
		$toggle_button_hide = $xpath->query( '//button[ @class = "hide-additional-facepiles" ]' )->item( 0 );

		if ( $toggle_button_show instanceof DOMElement && $toggle_button_hide instanceof DOMElement ) {
			$toggle_button_show->parentNode->insertBefore(
				$this->create_amp_state( 'facepiletoggle', false ),
				$toggle_button_show
			);

			$toggle_button_show->setAttribute( 'on', 'tap:AMP.setState( { facepiletoggle: ! facepiletoggle } )' );
			$toggle_button_show->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( facepiletoggle ? " is-hidden" : "" )', wp_json_encode( $toggle_button_show->getAttribute( 'class' ) ) )
			);

			$toggle_button_hide->setAttribute( 'on', 'tap:AMP.setState( { facepiletoggle: ! facepiletoggle } )' );
			$toggle_button_hide->setAttribute(
				'data-amp-bind-class',
				sprintf( '%s + ( facepiletoggle ? "" : "is-hidden" )', wp_json_encode( $toggle_button_hide->getAttribute( 'class' ) ) )
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
