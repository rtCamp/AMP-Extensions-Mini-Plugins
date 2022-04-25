# AMP support/compatibility for WordPress PDF Embedder Plugin by Lever Technology LLC.

The plugin de-register custom javascripts and replaced embedder link with amp-google-document-embed

## Plugin Structure

```markdown
.
├── css
│   ├── amp-style.css
├── sanitizers
│   ├── class-sanitizer.php
└── amp-pdf-embedder-compat.php
```
## Sanitizers

The plugin uses `amp_content_sanitizers` filter to add custom sanitizers, the sanitizer search and replaced link with class pdfemb-viewer with amp-google-document-embed component.

### Need a feature in plugin?
Feel free to create a issue and will add more examples.