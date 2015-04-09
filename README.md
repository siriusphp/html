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
// echo $h->make($tagName, $content, $props)
// where $props is an array containing
// 1. the HTML attributes and 
// 2. other data if the key starts with _

// the props are rendered in HTML tag if they don't start with _
// so be carefull what you put here
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




## Create HTML elements from classes

Obviously you can be very verbose if you like... to annoy other people.

```php

echo new Sirius\Html\Tag\Div(
	// the content (a string, array of strings, array of objects),
	[
		new Sirius\Html\Tag\Paragraph('This is a warning', ['class' => 'warning']),
		
		// input elements have values which are passed in the 3rd argument ($data) 
		// $data contains additional data required for rendering
		new Sirius\Html\Tag\Textarea('The content of the textarea', ['rows' => 30, 'cols' => 100]),
		new Sirius\Html\Tag\Select('US', [
			'_first_option' => 'Select a contry...',
			'_options' => ['US' => 'United States', 'UK' => 'United Kingdom', 'UC' => 'United colors']
		])
	],
	
	// the HTML attributes
	['id' => 'main', 'class' => 'container']
	
);

```

## Tag API

Once you get access to a `Tag` object you can do stuff with it:

##### `getProps($list)` | `setProps($props)`
$list = the names of the properties to be retrieved (null = ALL attributes)

##### `get($name)` | `set($name, $value)`

##### `addClass($class)` | `removeClass($class)` | `toggleClass($class)`

##### `getValue()` | `setValue($value)`
For form elements. They are aliases for `get('_value')` and `set('_value', $value)`

##### `getContent()` | `setContent($content)`
$content can be a string or an array. `getContent` returns an array that you can play with

##### `append($stringOrObject)` | `prepend($stringOrObject)`
To add something inside the tag at the beginning (`prepend`) or at the end (`append`)

##### `before($stringOrObject)` | `after($stringOrObject)`
To add something before or after the tag

##### `wrap($before, $after)`
So you don't have to call `before` and `after`