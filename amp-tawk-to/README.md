# AMP tawk.to Chat Bot

The plugin will add tawk.to chat bot to your WordPress site.

## How to use the plugin?

- Download and Install the plugin.
- Create a account on http://www.tawk.to/ 
- Add your site and create a chat widget (check tawk.to docs for help)
- Once Chat widget is created copy direct chat link
- Goto tawk.to dashboard -> Add On -> Chat Widget -> Direct Chat Link and copy it.
- Open your site goto Dashboard -> Settings -> Reading paste Direct Chat link into input field and Save.

## Plugin Limitations
- The plugin uses amp-iframe which requires to be loaded after 600px of viewport, so you will see chat button after scrolling down to the middle of site.
- The plugin may not very well on old devices with less than 600px viewport.
- The plugin won't work on non-AMP sites, you will have to use official plugin for non-AMP pages.
- If you are using Official tawk.to chat plugin, make sure you suppress it using AMP plugin's "plugin suppression" feature.

## Plugin Structure

```markdown
.
├── amp-tawk-to.php
├── css
│   └── amp-style.css
└── README.md
```
## Sanitizers

The plugin uses `amp-iframe` component to add direct chat link, inside a `amp-lightbox`, the plugin also uses `amp-position-observer` component to determine that `amp-iframe` opens after 600px viewport

## CSS
The plugin uses minor CSS to add chat button and lightbox.

### Need a feature in plugin?
Feel free to create a new support topic on AMP WordPress support forum