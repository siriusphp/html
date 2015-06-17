# Sirius HTML

[![Source Code](http://img.shields.io/badge/source-sirius/html-blue.svg?style=flat-square)](https://github.com/sirius/html)
[![Latest Version](https://img.shields.io/packagist/v/sirius/html.svg?style=flat-square)](https://github.com/sirius/html/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/sirius/html/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/sirius/html/master.svg?style=flat-square)](https://travis-ci.org/sirius/html)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/sirius/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/sirius/html/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/sirius/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/sirius/html)

Framework agnostic HTML rendering utility with an API inspired by jQuery and React.


## Create HTML elements using the builder

```php

$h = new Sirius\Html\Builder;

// at this point the builder knows only about the HTML tags of the library
// but you can add custom elements (classes or callbacks)
$h->registerTag('user-login', 'MyApp\Html\Tag\UserLogin');
$h->registerTag('app-footer', $someFunctionThatCreatesTags);

// the API is like this
// echo $h->make($tagName, $content, $props)
// where $props is an array containing
// 1. the HTML attributes and 
// 2. other data if the key starts with _

// the props are rendered in HTML tag if they don't start with _
// so be careful what you put here
$props = [
	'id' => 'some-id', 
	'class' => 'some classes here',
	'_some_private_data' => 'not treated as attribute'
];

// add content to your HTML
echo $h->make('p', [
		//  a string
		'This is a simple paragraph text',
		// or another HTML element
		$h->make('div', null, 'A DIV inside A Paragraph? No way!'),
		// or the stuff required to make another element
		['strong', [], 'this HTML library is bold']
	],
	$props
);

// the $data contains arbitrary values required for rendering
// in the case of a SELECT tag the $data will be something like
$props = [
	'name' => 'choice',
	'_first_option' => 'Select from list',
	'_options' => ['Option 1', 'Option 2', 'Option 3']
];
// for FORM elements the $content parameter is the value
$content = 'Option 2';
$select = $h->make('select', $content, $props);


// you can alter the tag using a jQuery-like API
$select->toggleClass('some-class');
$select->set('name', 'selected_option');
$select->setValue('Option 2');

echo $select;

// custom elements (most likely the custom elements rely only on $data)
echo $h->make('user-login', null, $someData);

// create HTML tags using the power of __call()
echo $h->h1('Heading 1');
echo $h->article(
	[
		$h->header([
			$h->h3('Post title')
		]),
		$h->aside('Aside content');
	],
	['class' => 'post']
);

// self-closing tags are like this 
echo $h->make('hr/', ['class' => 'separator'])
// this means __call() does not work in this case
```

The end goal of the library is composition so you can write your HTML like so

```php

echo $h->make('blog-article', [], ['_entry' => $someBlogPost]);

// which would be equivalent of 

echo $h->make(
	'article', 
	[
		['heading', [], $post->post_name],
		['section', [], $post->content],
		['footer', [], 'Written by ' . $post->author],
		['aside', [], [
			['h3', [], 'Similar articles'],
			['ul', [], [
				// ... you can guess what happens here 
			]]
		]]
	],
	['class' => 'post post-123 post-story']	
);
```



