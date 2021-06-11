# AMP Hide

A mini plugin to hide any element with class. default class `noamphtml`

## Notes

- The plugin provides filter `amp_hide_classes` you can pass array of classes you want to hide.
- by default plugin uses `noamphtml` class.
- You can either use noamphtml class in your theme or content or use the filter to add your own classes.
- The element will be completely removed to reduce DOM impact.
- Feel free to reach us on WordPress Support forum of AMP plugin.

## Filter Usages
You can use the filter as shown below add as many classes as you like!, to use copy paste code in your themes functions.php and change classes you want to hide.

```php
add_filter(
	'amp_hide_classes',
	function( $classes ) {
		$classes[] = 'hide-me-on-amp';
		$classes[] = 'dont-show-me-on-amp';
		return $classes;
	}
);
```

## Plugin Structure

```markdown
.
├── css
├── sanitizers
│   ├── class-sanitizer.php
└── amp-hide.php
```
## Sanitizers

The plugin uses `amp_content_sanitizers` filter to add custom sanitizers.

## Custom CSS
Please add custom CSS in Appearance->Customize->Additional CSS
AMP specific CSS can be added as `html[amp] .your-class-name`

### Need a feature in plugin?
Feel free to create a issue and will add more examples.