# AMP AD Compatibility Plugin
A WordPress Plugin replaces Ad unit codes and covert them into amp-ad.

## Notes
- The plugin replace `<ins class="adsbygoogle">` code with amp-ad.
- The plugin removes the script for ads since it's no longer needed.
- Plugin do not place ad code into your site, it just replaces existing codes with amp-ad on AMP endpoint.

## Usages Scenario
- Your theme have settings to add Ad unit code in different areas of theme like header or footer.
- You are using a plugin which places Ad unit codes but it won't work on AMP.

## What this plugin do?
### Ad unit code on non-AMP pages.
```markdown
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
style="display:inline-block;width:728px;height:90px"
data-ad-client="ca-pub-1234567890123456"
data-ad-slot="1234567890"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
```
### Converted into ad-unit on AMP page.
```markdown
<amp-ad width="728" height="90" type="adsense" data-ad-client="ca-pub-1234567890123456" data-ad-slot="1234567890" class="adsbygoogle">
</amp-ad>
```


## Plugin Structure
```markdown
.
├── sanitizers
│   ├── class-sanitizer.php
└── amp-ad-compat.php
```
## Sanitizers
The plugin uses `amp_content_sanitizers` filter to add custom sanitizers.

### Need a feature in plugin?
Feel free to create a issue and will add more examples.