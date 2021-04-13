<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_PDF_Embedder_Compat
 */

namespace Google\AMP_PDF_Embedder_Compat;

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

		// get pdf embedder link.
		$pdf_embedder_links = $xpath->query( '//a[ @class = "pdfemb-viewer" ]' );
		if ( $pdf_embedder_links instanceof \DOMNodeList ) {
			foreach ( $pdf_embedder_links as $pdf_embedder_link ) {
				if ( $pdf_embedder_link instanceof \DOMElement ) {
					$pdf_embedder_link->parentNode->replaceChild(
						$this->create_amp_google_document_embed( $pdf_embedder_link ),
						$pdf_embedder_link
					);
				}
			}
		}

	}

	/**
	 * Create Google Document Embed Element.
	 *
	 * @param string $pdf_embedder_link  PDF Link.
	 *
	 * @return DOMElement An amp_google_document_embed element.
	 */
	private function create_amp_google_document_embed( $pdf_embedder_link ) {

		$pdf_file_link = $pdf_embedder_link->getAttribute( 'href' );

		$amp_google_doc_embed = $this->dom->createElement( 'amp-google-document-embed' );
		$amp_google_doc_embed->setAttribute( 'src', $pdf_file_link );
		$amp_google_doc_embed->setAttribute( 'width', '800' );
		$amp_google_doc_embed->setAttribute( 'height', '600' );
		$amp_google_doc_embed->setAttribute( 'layout', 'responsive' );

		return $amp_google_doc_embed;
	}
}
