<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Hide
 */

namespace Google\AMP_Hide;

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

		$amp_hide_classes = apply_filters( 'amp_hide_classes', array( 'noamphtml' ) );

		if ( ! empty( $amp_hide_classes ) ) {

			foreach ( $amp_hide_classes as $amp_hide_class ) {
				// Remove google adsense scripts.
				$elements = $xpath->query( '//*[contains( @class, "' . $amp_hide_class . '" ) ]' );

				if ( $elements instanceof \DOMNodeList ) {
					foreach ( $elements as $element ) {
						if ( $element instanceof \DOMElement ) {
							$element->parentNode->removeChild( $element );
						}
					}
				}
			}
		}

	}

}
