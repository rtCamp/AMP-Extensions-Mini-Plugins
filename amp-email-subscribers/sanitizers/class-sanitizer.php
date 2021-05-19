<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Email_Subscribers_Compat
 */

namespace Google\AMP_Email_Subscribers_Compat;

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
		$es_forms = $xpath->query( '//form [contains(@class,"es_subscription_form")]' );

		if ( $es_forms instanceof \DOMNodeList ) {
			foreach ( $es_forms as $es_form ) {
				if ( $es_form instanceof DOMElement ) {

					if ( $es_form->childNodes instanceof \DOMNodeList ) {
						$es_form_nodes = $es_form->getElementsByTagName( 'input' );
						foreach ( $es_form_nodes as $es_form_node ) {
							if ( $es_form_node instanceof \DOMElement ) {
								if ( 'input' === $es_form_node->nodeName ) {
									$es_form_node->parentNode->replaceChild(
										$this->make_list_element( $es_form_node ),
										$es_form_node
									);
								}
							}
						}
					}

					// make it external action.
					$es_form->appendChild( $this->create_hidden_input( 'ig_es_external_action', 'subscribe' ) );
					// Add IP addess.
					$es_form->appendChild( $this->create_hidden_input( 'ip_address', '' ) );
				}
			}
		}

	}

	/**
	 * Create Hidden Input
	 *
	 * @param string $name input type name attribute.
	 * @param string $value input type value attribute.
	 * @return DomElement amp input hidden field.
	 */
	private function create_hidden_input( $name, $value ) {

		$amp_hidden_input = $this->dom->createElement( 'input' );
		$amp_hidden_input->setAttribute( 'type', 'hidden' );
		$amp_hidden_input->setAttribute( 'name', $name );
		$amp_hidden_input->setAttribute( 'value', $value );

		return $amp_hidden_input;

	}

	/**
	 * Search and Replace name attributes for list hash.
	 *
	 * @param string $es_form_node Child Node.
	 * @return DOMElement Child Node with replaced attribute.
	 */
	private function make_list_element( $es_form_node ) {
		$es_form_node_attribute_name = $es_form_node->getAttribute( 'name' );

		if ( 'lists[]' === $es_form_node_attribute_name ) {
			$es_form_node->removeAttribute( 'lists[]' );
			$es_form_node->setAttribute( 'name', 'list' );
		}
		return $es_form_node;
	}
}
