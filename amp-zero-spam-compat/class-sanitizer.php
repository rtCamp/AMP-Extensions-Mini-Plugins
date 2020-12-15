<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_WP_Zero_Spam_Compat
 */

namespace Google\AMP_WP_Zero_Spam_Compat;

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

		$xpath            = new DOMXPath( $this->dom );
		$wp_zero_spam_key = wpzerospam_get_key();

		// Get the CF7.
		$cf7_forms = $xpath->query( '//form [contains(@class,"wpcf7-form")]' );
		if ( $cf7_forms instanceof \DOMNodeList ) {
			foreach ( $cf7_forms as $form ) {
				if ( $form instanceof \DOMElement ) {
					// Add attribute and append hidden element.
					$form->setAttribute( 'data-wpzerospam', 'protected' );
					$form->appendChild( $this->create_hidden_input( $wp_zero_spam_key ) );
				}
			}
		}

		// Get the BuddyPress.
		$buddypress_forms = $xpath->query( '//form [contains(@id,"signup-form")]' );
		if ( $buddypress_forms instanceof \DOMNodeList ) {
			foreach ( $buddypress_forms as $form ) {
				if ( $form instanceof \DOMElement ) {
					// Add attribute and append hidden element.
					$form->setAttribute( 'data-wpzerospam', 'protected' );
					$form->appendChild( $this->create_hidden_input( $wp_zero_spam_key ) );
				}
			}
		}

		// Get the Comments.
		$comments_forms = $xpath->query( '//form [contains(@id,"commentform")]' );
		if ( $comments_forms instanceof \DOMNodeList ) {
			foreach ( $comments_forms as $form ) {
				if ( $form instanceof \DOMElement ) {
					// Add attribute and append hidden element.
					$form->setAttribute( 'data-wpzerospam', 'protected' );
					$form->appendChild( $this->create_hidden_input( $wp_zero_spam_key ) );
				}
			}
		}

		// Fluent Forms.
		$fluent_forms = $xpath->query( '//form [contains(@class,"frm-fluent-form")]' );
		if ( $fluent_forms instanceof \DOMNodeList ) {
			foreach ( $fluent_forms as $form ) {
				if ( $form instanceof \DOMElement ) {
					// Add attribute and append hidden element.
					$form->setAttribute( 'data-wpzerospam', 'protected' );
					$form->appendChild( $this->create_hidden_input( $wp_zero_spam_key ) );
				}
			}
		}

	}

	/**
	 * Create Hidden Input
	 *
	 * @param string $wp_zero_spam_key WP Zero key.
	 * @return DomElement amp input hidden field.
	 */
	private function create_hidden_input( $wp_zero_spam_key ) {

		$amp_hidden_input = $this->dom->createElement( 'input' );
		$amp_hidden_input->setAttribute( 'type', 'hidden' );
		$amp_hidden_input->setAttribute( 'name', 'wpzerospam_key' );

		if ( function_exists( 'wpzerospam_get_key' ) ) {
			$amp_hidden_input->setAttribute( 'value', $wp_zero_spam_key );
		}
		return $amp_hidden_input;

	}

}
