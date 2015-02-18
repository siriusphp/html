# Sirius HTML

Framework agnostic HTML rendering utility with an API inspired by jQuery and React.

## Create HTML elements using the builder

```php

$h = new Sirius\Html\Builder;
// at this point the builder knows only about the HTML tags of the library
// but you can add custom elements (classes or callbacks)
$h->addElement('user-login', 'MyApp\Html\Tag\UserLogin');
$h->addElement('app-footer', $someFunctionThatCreatesTags);

// each element can have attributes, content and optional data
$attrs = ['id' => 'some-id', 'class' => 'some classes here'];

// the content can be a string, an array or an object that has __toString()
$content = 'This is a paragraph';
$content = [
	'This is a simple text',
	$h->make('div', null, 'This is a DIV'),
];

// the $data contains arbitrary values required for rendering
// in the case of a SELECT tag the $data may be something like
$data = [
	'first_option' => 'Select from list',
	'options' => ['Option 1', 'Option 2', 'Option 3'],
	'value' => 'Option 3'
];

$select = $h->make('select', $attrs, $content, $data);
// you can alter the tag using a jQuery-like API
$select->toggleClass('some-class');
$select->setAttribute('name' => 'selected_option');
$select->setValue('Option 2');

echo $select;

// custom elements
echo $h->make('user-login');

// create HTML tags using the power of __call()
$h->h1(null, 'Heading 1');
$h->article(['class' => 'post'], [
	$h->header([], [
		$h->h3([], 'Post title')
	]),
	$h->aside([], null);
]);

```

## Create HTML elements from classes

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

##### `getAttributes($list)` | `setAttributes($attrs)`
$list = the names of the attributes to be retrieved (null = ALL attributes)

##### `getAttribute($name)` | `setAttribute($name, $value)`

##### `addClass($class)` | `removeClass($class)` | `toggleClass($class)`

##### `getData($list)` | `setData($nameOrArray, $value)`
$list = the keys of the data array to be retrieved (null = ALL attributes)

##### `getValue()` | `setValue($value)`
For form elements. They are aliases for `getData('value')` and `setData('value', $value)`

##### `getContent()` | `setContent($content)`
$content can be a string or an array (associative or not)

#### `before($stringOrObject)` | `after($stringOrObject)`
To insert something before or after the tag

#### `wrap($before, $after)`
So you don't have to call `before` and `after`