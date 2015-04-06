# Sirius HTML

[![Build Status](https://scrutinizer-ci.com/g/siriusphp/html/badges/build.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/html/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/siriusphp/html/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/html/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/siriusphp/html/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/html/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/siriusphp/html/v/stable.png)](https://packagist.org/packages/siriusphp/html)
[![License](https://poser.pugx.org/siriusphp/html/license.png)](https://packagist.org/packages/siriusphp/html)


Framework agnostic HTML rendering utility with an API inspired by jQuery and React.

## Create HTML elements using the builder

```php

$h = new Sirius\Html\Builder;

// at this point the builder knows only about the HTML tags of the library
// but you can add custom elements (classes or callbacks)
$h->registerTag('user-login', 'MyApp\Html\Tag\UserLogin');
$h->registerTag('app-footer', $someFunctionThatCreatesTags);

// the API is like this
// echo $h->make($tagName, $attributes, $content, $data)

// the attributes are rendered in HTML tag without any filtering
// so be carefull what you put here
$attrs = ['id' => 'some-id', 'class' => 'some classes here'];

// add content to your HTML
echo $h->make('p', $attrs, [
	//  a string
	'This is a simple paragraph text',
	// or another HTML element
	$h->make('div', null, 'A DIV inside A Paragraph? No way!'),
	// or the stuff required to make another element
	['strong', [], 'this HTML library is bold']
]);

// the $data contains arbitrary values required for rendering
// in the case of a SELECT tag the $data will be something like
$data = [
	'first_option' => 'Select from list',
	'options' => ['Option 1', 'Option 2', 'Option 3']
];
// for FORM elements the $content is the value
$content = 'Option 2';
$select = $h->make('select', $attrs, $content, $data);


// you can alter the tag using a jQuery-like API
$select->toggleClass('some-class');
$select->setAttribute('name' => 'selected_option');
$select->setValue('Option 2');

echo $select;

// custom elements (most likely the custom elements rely only on $data)
echo $h->make('user-login', null, null, $someData);

// create HTML tags using the power of __call()
echo $h->h1(null, 'Heading 1');
echo $h->article(['class' => 'post'], [
	$h->header(null, [
		$h->h3(null, 'Post title')
	]),
	$h->aside(null, 'Aside content');
]);

// self-closing tags are like this (so no __call() for you in this case)
echo $h->make('hr/', ['class' => 'separator'])

```

The end goal of the library is composition so you can write your HTML like so

```php

echo $h->make('blog-article', [], [], ['entry' => $someBlogPost]);

// which would be equivalent of 

echo $h->make(
	'article', 
	['class' => 'post post-123 post-story'],
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
	]
);
```




## Create HTML elements from classes

Obviously you can be very verbose if you like... to annoy other people.

```php

echo new Sirius\Html\Tag\Div(
	// the HTML attributes
	['id' => 'main', 'class' => 'container'],
	
	// the content (a string, array of strings, array of objects),
	[
		new Sirius\Html\Tag\Paragraph(['class' => 'warning'], 'This is a warning'),
		
		// input elements have values which are passed in the 3rd argument ($data) 
		// $data contains additional data required for rendering
		new Sirius\Html\Tag\Textarea(['rows' => 30, 'cols' => 100], null, ['value' => 'The content of the textarea']),
		new Sirius\Html\Tag\Select(null, null, [
			'first_option' => 'Select a contry...',
			'options' => ['US' => 'United States', 'UK' => 'United Kingdom', 'UC' => 'United colors'],
			'value' => 'US'
		])
	]
);

```

## Tag API

Once you get access to a `Tag` object you can do stuff with it:

##### `getAttributes($list)` | `setAttributes($attrs)`
$list = the names of the attributes to be retrieved (null = ALL attributes)

##### `getAttribute($name)` | `setAttribute($name, $value)`

##### `addClass($class)` | `removeClass($class)` | `toggleClass($class)`

##### `getData($list)` | `setData($nameOrArray, $value)`
$list = the keys of the data array to be retrieved (null = ALL attributes)

##### `getValue()` | `setValue($value)`
For form elements. They are aliases for `getData('value')` and `setData('value', $value)`

##### `getContent()` | `setContent($content)`
$content can be a string or an array. `getContent` returns an array that you can play with

##### `append($stringOrObject)` | `prepend($stringOrObject)`
To add something inside the tag at the beginning (`prepend`) or at the end (`append`)

##### `before($stringOrObject)` | `after($stringOrObject)`
To add something before or after the tag

##### `wrap($before, $after)`
So you don't have to call `before` and `after`