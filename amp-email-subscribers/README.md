# AMP compatibility for Email Subscribers & Newsletters – Simple and Effective Email Marketing WordPress Plugin by icegram

## What it does?

- Removes Scripts.
- Submits subscription form as external.
- Since it does Doing Ajax check we have to use external submission
- Add hidden inputs for external form.
- Replace lists[] attribute with list.

## Plugin Structure

```markdown
.
├── sanitizers
│   ├── class-sanitizer.php
└── amp-skeleton-compat.php
```
## Sanitizers

The plugin uses `amp_content_sanitizers` filter to add custom sanitizers. to search and replaces lists and to add hidden attributes.

### Need a feature in plugin?
Contact me on https://wpindia.co.in/