<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_FB_Comments_Compat
 */

namespace Google\AMP_FB_Comments_Compat;

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

		$facebook_roots    = $xpath->query( '//div[ @id = "fb-root" ]' );
		$facebook_comments = $xpath->query( '//div[ @class = "fb-comments" ]' );
		$remove_script     = $xpath->query( '//script[contains(., "loadLFCComments")]' )->item( 0 );

		if ( $remove_script instanceof \DOMElement ) {
			// Remove inline script added.
			$remove_script->parentNode->removeChild( $remove_script );
		}

		if ( $facebook_roots instanceof \DOMNodeList ) {
			foreach ( $facebook_roots as $key => $facebook_root ) {
				if ( $facebook_root instanceof \DOMElement ) {
					// Add attribute and append hidden element.
					$facebook_root->parentNode->removeChild( $facebook_root );
				}
			}
		}

		if ( $facebook_comments instanceof \DOMNodeList ) {
			foreach ( $facebook_comments as $key => $facebook_comment ) {
				if ( $facebook_comment instanceof \DOMElement ) {
					$facebook_comment->parentNode->replaceChild( 
						$this->create_amp_facebook_comments( $facebook_comment ),
						$facebook_comment
					);
				}
			}
		}

	}

	/**
	 * Add Facebook comments components.
	 *
	 * @param instance $facebook_comment Facebook Comment DOMElement.
	 */
	public function create_amp_facebook_comments( $facebook_comment ) {
		$width = $facebook_comment->getAttribute( 'data-width' );

		$height = '394';

		if ( ! empty( $width ) ) {
			// Added 16:9 asepct ratio height.
			$height = round( $width / 1.77777777778 );
		}

		$height      = $facebook_comment->getAttribute( 'data-height' );
		$count       = $facebook_comment->getAtttribute( 'data-numposts' );
		$url         = $facebook_comment->getAttribute( 'data-href' );
		$colorscheme = $facebook_comment->getAttribute( 'data-colorscheme' );
		$order_by    = $facebook_comment->getAttribute( 'data-order-by' );

		$amp_facebook_comments = $this->dom->createElement( 'amp-facebook-comments' );
		$amp_facebook_comments->setAttribute( 'layout', 'responsive' );
		$amp_facebook_comments->setAttribute( 'width', $width );
		$amp_facebook_comments->setAttribute( 'height', $height );
		$amp_facebook_comments->setAttribute( 'data-numposts', $count );
		$amp_facebook_comments->setAttribute( 'data-href', $url );
		$amp_facebook_comments->setAttribute( 'data-colorscheme', $colorscheme );
		$amp_facebook_comments->setAttribute( 'data-order-by', $order_by );

		return $amp_facebook_comments;

	}

}
