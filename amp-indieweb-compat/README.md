# AMP compatibility plugin for IndieWeb WordPress Plugins.

The plugin will provide AMP compatibility for IndieWeb Plugins and SemPress theme.

## Plugins

- IndieWeb Plugin
- Webmention
- Semantic-Linkbacks
- Syndication Links
- Micropub
- Post Kinds
- IndieAuth
- Microformats 2
- Simple Location
- WebSub/PubSubHubbub
 

## Themes Supported
- Sempress

## Known Limitation 

The Post Kinds plugin uses script to add timestamp to URL as a hash http://example.com/#t=5, 
I am not sure if the amp component for video and audio has support for it. also not sure if amp-script will help given that it runs in Worker DOM. I am still looking for a solution.
This is not a vital functionality and can be ignored, we can just dequeue scripts to make plugin AMP compatible.

## Plugin Structure

```markdown
.
├── css
│   ├── amp-style.css
├── sanitizers
│   ├── class-sanitizer.php
└── amp-skeleton-compat.php
```
## Sanitizers

The plugin uses `amp_content_sanitizers` filter to add custom sanitizers, we have added a two examples which add simple toggle for menu and search using amp-state and amp-bind.

### Need a feature in plugin?
Feel free to create a issue and will add more examples.