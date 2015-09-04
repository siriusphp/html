---
title: HTML tag API
---

# Tag API

The library is inspired by ReactJS and jQuery and it has a very small and familiar API. 
If you use the library just to render HTML in your views you will just pass an array of `props` and `content` but if you want to use it as a base for another library or extend its functionality (with decorators) you need to know the API

```
$tag = $h->make('div', $props, $content);

// change attributes/props
$tag->set('class', 'required');
$tag->set('_hidden_property', 'some value');
$tag->setProps([
    'class' => 'required',
    '_hidden_property' => 'some value'
]);

// retrieve attribues/props
$tag->get('class');
$tag->getProps();

// manipulate the content
$content = $tag->getContent(); // this is an array of tags
$content[] = 'additional content';
$tag->setContent($content);
// or
$tag->setContent('new content');
$tag->setContent([
    $h->p(null, 'parapgrah'),
    $h->div(null, 'div tag')
]);
// you can empty the tag
$tag->clearContent();

// manipulate the "class" attribute
$tag->addClass('required');
$tag->removeClass('required');
$tag->toggleClass('required');
$tag->hasClass('required');

// append/prepend stuff to content
$tag->append('added before $content');
$tag->append($h->p(null, 'you can do tags as well'));
$tag->prepend('added after $content');

// before/after output
$tag->before('added before <div');
$tag->after('added after </div>');
$tag->wrap('this is added before', 'this is added after');
// you can add as many items before and after
```

## Form input tags are special

1. The input tags have `setValue()` and `getValue()` 
2. Using `getContents` and `setContents` for an input tag behaves like `getValue()` and `setValue()` 

```php
$input = $h->text(null, 'myemail@domain.com');
$input->setValue('anotheremail@domain.com');
$input->setContent('anotheremail@domain.com'); //same thing
``