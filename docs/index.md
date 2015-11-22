# Sirius HTML

[![Source Code](http://img.shields.io/badge/source-siriusphp/html-blue.svg?style=flat-square)](https://github.com/siriusphp/html)
[![Latest Version](https://img.shields.io/packagist/v/siriusphp/html.svg?style=flat-square)](https://github.com/siriusphp/html/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/siriusphp/html/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/siriusphp/html/master.svg?style=flat-square)](https://travis-ci.org/siriusphp/html)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/siriusphp/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/html/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/siriusphp/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/html)

Framework agnostic HTML rendering utility with an API inspired by jQuery and React.

```php

$h = new Sirius\Html\Builder;
// at this point the builder knows only about the HTML tags defined in the library
// ie: paragraph, div, radio, select, checkbox, img, textarea etc

// start writting HTML
echo $h->make('paragraph', ['href' => 'http://www.bing.com'], 'Go to Bing!']

// or use the power of magic methods
echo $h->h1(['class' => 'main'], ['Main title', $h->em(null, '!!!')]);

// the library is smart enough to handle special requests
echo $h->someComponent(['class' => 'web-component'], 'content of the component');
// will render <some-component class="web-component">content of the component</some-component>

```

Build your own HTML components

```php

// register your component as classes or callbacks
$h->registerTag('my-component', '\\MyProject\\Html\\MyComponent');
$h->registerTag('my-component', $someCallable);

echo $h->myComponent(null, 'My component content');

```

The end goal of the library is to allow you write compose your HTML views like so

```php

echo $h->make('blog-article', [], ['_entry' => $someBlogPost]);

// which would be equivalent of 

echo $h->make(
	'article', 
	[
		['heading', $post->post_name],
		['section', $post->content],
		['footer', 'Written by ' . $post->author],
		['aside', [
			['h3', 'Similar articles'],
			['ul', [
				// ... you can guess what happens here 
			]]
		]]
	],
	['class' => 'post post-123 post-story']	
);
```



