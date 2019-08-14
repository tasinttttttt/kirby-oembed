# Kirby 3 - oEmbed

oEmbed kirby field

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

// Necessary to get access to other field methods.
$oembed = $page->myfield()->toOembed();

echo $oembed->getTitle();
// Outputs: BAZOGA \u2014 TFHEM TSETTA #1

echo $oembed->getEmbedCode();
// Outputs:
// "\u003ciframe width=\"480\" height=\"270\" src=\"https:\/\/www.youtube.com\/embed\/M6zR09nn9gE?feature=oembed\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen\u003e\u003c\/iframe\u003e"

echo $oembed;
// Outputs a string representation of the data. (debug purposes)
```

## Template Methods

**You need to call `$page->fieldname()->toOembed()` to get access to methods.**

Method | Return Type | Description
--- | --- | ---
`getData()` | `array` | Returns the full json response.
`getEmbedCode` | `string` | Returns the embed code.
`getTitle()` | `string` | Returns the title if available.
`getDescription()` | `string` | Returns the description if available.
`getThumbnail()` | `array` | Returns an array with thumbnail information. Keys are: `thumbnail_url`, `thumbnail_width`, `thumbnail_height`. All keys are returned, but all may not contain data.
`getId()` | `string` | Returns the id if available. (ex. `http://youtube.com/watch?v=ferZnZ0_rSM` returns `ferZnZ0_rSM`)
`getKey($key)` | `mixed` | Returns the value for the provided `$key`, if available.


## Static Methods

Method | Return Type | Description
--- | --- | ---
`Oembed::getIdByUrl($url)` | `string` | Returns an embed id if available (ex. `http://youtube.com/watch?v=ferZnZ0_rSM` returns `ferZnZ0_rSM `)
`Oembed::getProviderByUrl($url)` | `string` | Returns the kind of provider (youtube, vimeo, soundcloud, twitch) for the provided `$url`.
`Oembed::getOembedUrl($url)` | `string` | Returns the oembed url for the provided `$url`.
`Oembed::getEmbedCodeByUrl($url)` | `string` | Returns the embed code for the provided `$key`, if available.
`Oembed::getOembedJsonByUrl($url)` | `array` | Returns the full json oembed response as an array. Throws an `Exception` if the url is incorrect or if nothing was returned by the provider.