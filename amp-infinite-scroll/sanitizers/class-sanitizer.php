<?php
/**
 * Sanitizer
 *
 * @package Google\AMP_Infinite_Scroll
 */

namespace Google\AMP_Infinite_Scroll;

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

		$amp_infinite_scroll_configs = apply_filters(
			'amp_infinite_scroll_configs',
			array(
				'footer'         => array( 'site-footer' => 'footer' ),
				'next_page_hide' => array( 
					'widget-area' => 'aside',
					'pagination'  => 'nav',
					'site-header' => 'header',
				),
			)
		);
		
		$footer_elements = $amp_infinite_scroll_configs['footer'];
		if ( ! empty( $footer_elements ) ) {
			foreach ( $footer_elements as $footer_element_class => $footer_element ) {
				$footer = $xpath->query( '//' . $footer_element . '[contains(@class, "' . $footer_element_class . '")]' )->item( 0 );
				if ( $footer instanceof \DOMElement ) {
					$footer->parentNode->replaceChild(
						$this->create_amp_next_page( $footer ),
						$footer
					);
				}
			}
		}
		
		$next_page_hides = $amp_infinite_scroll_configs['next_page_hide'];
		if ( ! empty( $next_page_hides ) ) {
			foreach ( $next_page_hides as $hide_element_class => $hide_element ) {
				$hide_me = $xpath->query( '//' . $hide_element . '[contains(@class, "' . $hide_element_class . '")]' )->item( 0 );
				if ( $hide_me instanceof \DOMElement ) {
					$hide_me->setAttribute( 'next-page-hide', '' );
				}
			}
		}

		$amp_switcher = $xpath->query( '//div[@id="amp-mobile-version-switcher"]' )->item( 0 );

		if ( $amp_switcher instanceof \DOMElement ) {
			$amp_switcher->setAttribute( 'next-page-hide', '' );
		}
	}

	/**
	 * Create AMP Next Page Node.
	 *
	 * @param instanceof $footer footer.
	 */
	private function create_amp_next_page( $footer ) {

			$amp_next_page = $this->dom->createElement( 'amp-next-page' );
			$amp_next_page->setAttribute( 'max-pages', esc_attr( $this->amp_get_max_pages() ) );
			$amp_next_page->appendChild( $this->create_amp_next_page_script_tag() );

			$next_page_footer = $this->dom->createElement( 'div' );
			$next_page_footer->setAttribute( 'footer', '' );
			
			// This took me 2 days to figure out :D.
			$next_page_footer->appendChild( $footer->cloneNode( true ) );

			$amp_next_page->appendChild( $next_page_footer );
			return $amp_next_page;
	}

	/**
	 * Add Script tag.
	 */
	private function create_amp_next_page_script_tag() {
		$script_tag = $this->dom->createElement( 'script' );
		$script_tag->setAttribute( 'type', 'application/json' );
		$script_tag->textContent = '[' . \wp_json_encode( $this->amp_next_page() ) . ']';
		return $script_tag;
	}

	/**
	 * Get the AMP next page information.
	 *
	 * @return array
	 */
	protected function amp_next_page() {
		$title = '';
		$url   = '';
		$image = '';

		if ( ! static::amp_is_last_page() ) {
			$title = sprintf(
				'%s - %s %d - %s',
				wp_title( '', false ),
				__( 'Page', 'jetpack' ),
				max( get_query_var( 'paged', 1 ), 1 ) + 1,
				get_bloginfo( 'name' )
			);
			$url   = get_next_posts_page_link();
		}

		$next_page = array(
			'title' => $title,
			'url'   => $url,
			'image' => $image,
		);

		/**
		 * The next page settings.
		 * An array containing:
		 *  - title => The title to be featured on the browser tab.
		 *  - url   => The URL of next page.
		 *  - image => The image URL. A required AMP setting, not in use currently. Themes are welcome to leverage.
		 *
		 * @module infinite-scroll
		 *
		 * @since 9.0.0
		 *
		 * @param array $next_page The contents of the output buffer.
		 */
		return apply_filters( 'jetpack_amp_infinite_next_page_data', $next_page );
	}

	/**
	 * Get the number of pages left.
	 *
	 * @return int
	 */
	protected static function amp_get_max_pages() {
		global $wp_query;

		return (int) $wp_query->max_num_pages - $wp_query->query_vars['paged'];
	}

	/**
	 * Is the last page.
	 *
	 * @return bool
	 */
	protected static function amp_is_last_page() {
		return 0 === static::amp_get_max_pages();
	}
}
