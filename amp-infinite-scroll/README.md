# AMP Infinite Scroll

The AMP plugin for infinite Scroll.

## Notes

- The plugin will only work AMP endpoint on limited pages mentioned, you will have to find other solutions for non-AMP pages.
- The plugin will only work on archive, home, and Search result pages.
- You will have to add extra pice of code for the filter `amp_infinite_scroll_configs` in your themes functions.php or in a custom plugin.
- Whilte testing please close browser debugging tool and Logout or hide Admin toolbar.
- If you any question feel free to contact [AMP WordPress Support](!https://wordpress.org/support/plugin/amp/#new-topic-0)

Usages
```php
add_filter( 'amp_infinite_scroll_configs', function( $config ) {
    $config = array(
		'footer'         => array( 'site-footer' => 'footer' ),
		'next_page_hide' => array( 
			'widget-area' => 'aside',
			'pagination'  => 'nav',
			'site-header' => 'header',
		),
	);
    return $config;
} );
```

#### Footer 
The first `footer` needs class as key and elemnt as value in a array, the footer will be displayed at very bottom of the Pagination and will be pushed down till you reach page limit which is 10.

#### Next Page Hide
The second `next_page_hide` The elements such as header, widget area which you don't want to duplicate to avoid on next page can be added using class element pair, where class is key and element as value.

## Plugin Structure

```markdown
.
├── css
│   ├── amp-style.css
├── sanitizers
│   ├── class-sanitizer.php
└── amp-infinite-scroll.php
```
## Sanitizers

The plugin uses `amp_content_sanitizers` filter to add custom sanitizers, also uses amp-next-page AMP compononet for Infinite scroll feature.

## Custom CSS
You can add your custom CSS or override the CSS in in `css/amp-style.css` make sure you don't exceed overall budget of 75KB

### Need a feature in plugin?
Feel free to create a issue and will add more examples.
