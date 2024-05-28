# Kirby 3 - oEmbed

Adds oEmbed to kirby.

![|400](https://github.com/tasinttttttt/kirby-oembed/assets/2914169/5da016be-ac1f-47e5-b98f-8d0205bd3a7b)

## Installation

- unzip [master.zip](https://github.com/tasinttttttt/kirby-oembed/archive/master.zip) as folder `site/plugins/oembed` or
- `git submodule add https://github.com/tasinttttttt/kirby-oembed.git site/plugins/oembed` or
- `composer require tasinttttttt/oembed`

## Setup

**In Blueprint:**

```yaml
myfield:
	type: oembed
	title: My oEmbed Field
```

**In File:**

```txt
...
Myfield:
- https://www.youtube.com/watch?v=M6zR09nn9gE
...

```
**In Template:**

```php
<?php

// Import css and js to render iframes
echo css('media/plugins/tasinttttttt/oembed/oembed.css');
echo js('media/plugins/tasinttttttt/oembed/oembed.js');

// Necessary to get access field methods.
$oembed = $page->myfield()->toOembed();

// Outputs an iframe with clickable thumbnail and autoplay
echo $oembed->getEmbed();
```

## Template Methods

**You need to call `$page->fieldname()->toOembed()` to get access to methods.**

Method | Return Type | Description
--- | --- | ---
`getData()` | `array` | Returns the full json response.
`getEmbed()` | `string` | Returns a clickable html element with thumbnail and iframe.
`getEmbedCode($safe = false)` | `string` | Returns the embed code., `$safe` determines whether the output has escaped html or not. Use at your own risk.
`getEmbedUrl()` | `string` | Returns the embed url.
`getTitle()` | `string` | Returns the title if available.
`getDescription()` | `string` | Returns the description if available.
`getProvider()`
`getThumbnail()` | `array` | Returns an array with thumbnail information. Keys are: `thumbnail_url`, `thumbnail_width`, `thumbnail_height`. All keys are returned, but all may not contain data.
`getId()` | `string` | Returns the id if available. (ex. `http://youtube.com/watch?v=ferZnZ0_rSM` returns `ferZnZ0_rSM`)
`getKey($key)` | `mixed` | Returns the value for the provided `$key`, if available.


## Static Methods

Method | Return Type | Description
--- | --- | ---
`Oembed::getIdByUrl($url)` | `string` | Returns an embed id if available (ex. `http://youtube.com/watch?v=ferZnZ0_rSM` returns `ferZnZ0_rSM `)
`Oembed::getProviderByUrl($url)` | `string` | Returns the kind of provider (youtube, vimeo, soundcloud, twitch) for the provided `$url`.
`Oembed::getOembedUrl($url)` | `string` | Returns the oembed url for the provided `$url`.
`Oembed::getEmbedCodeByUrl($url)` | `string` | Returns the embed code for the provided `$key`, if available.
`Oembed::getOembedJsonByUrl($url)` | `array` | Returns the full json oembed response as an array. Throws an `Exception` if the url is incorrect or if nothing was returned by the provider.
