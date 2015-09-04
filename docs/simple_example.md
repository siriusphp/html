---
title: Simple example
---

# Simple example

Without any preparation you can write HTML like this

```php

// a list of options for the builder instance
// this is useful when you implement custom tags 
// that need access to some sort of global state
$options = array(
	'use_boostrap' => true
);
$h = new Sirius\Html\Builder($options);

echo $h->make(
	/* name of the tag */
	'article',
	
	/* properties of the tag */
	['class' => 'post']
	
	/* content of the tag (a string or a list of elements/strings) */
	[
		$h->make('hr/'),
		
		'Random text node',
		
		/* you can construct tags using the magic of __call() */
		$h->header([
			$h->h3('Post title')
		]),
		$h->aside('Aside content'),
	],
);
```

This might seem trivial but the power of the library is that it allows you to write custom HTML tags. So instead of the code above you can write

```php
$h->blogPost([
    'class' => 'post',
    
    /* properties with _ as prefix are not treated as attributes */
    '_title' => 'Post title',
    '_content' => 'Aside content'
]);
```


## Form input tags are special

For form elements the content of the tag is the value. So you have to do something like

```php

$h->input([
	'type' => 'email',
	'class' => 'required'
], 'myemail@domain.com');

$h->select([
	'_first_option' => 'Select from list',
	'_options' => ['Romania', 'USA', 'Zimbawe']
], 'Romania');
```

## Cloning the tag builder

Sometimes you might need to render some tag using a different configuration. Let's say you have a tag builder that you use to render input elements using Bootstrap classes.

```php
$b = new Sirius\Html\Builder(['boostrap_styles' => true]);
$b->text(["name" => "email"], 'email@domain.com');
```

At some point you want to render some input elements without those styles but you want to keep the rest of builder's capabilities (registered tags, other options)

```php
$newB = $b->with(['bootstrap_styles' => false]);
$newB->text(["name" => "email"], 'email@domain.com');
```

This situation is useful if you use decorators, events or other ways to change the default rendering behaviour.