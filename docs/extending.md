---
title: Extending the tag builder
---

# Extending the tag builder

Each application has different requirements. Some may use filters to alter the behavior (ala Wordpress), others may use events, other may need to implement a decorator mechanism (eg: to automatically add Bootstrap classes to form elements)

## Using decorators


```php

namespace MyApp\Html;

class Builder extends Sirius\Html\Builder {

    protected $decorators = [];
    
    function addDecorator($decorator) {
        $this->decorators[] = $decorator;
    }
    
    protected function decorateTag($tag) {
        foreach ($this->decorators as $decorator) {
            $tag = $decorator->decorate($tag, $builder);
        }
        return $tag;
    }
    
    function make($tag, $props = null, $content = null)
        $tag = parent::make($tag, $props, $content);
        $tag = $this->decorateTag($tag);
        return $tag
    }

}
```

One decorator could be something like

```php
namespace MyApp\Html\Decorators;

class Bootstrap {

    function decorate($tag, $builder) {
        if ($tag instance of Sirius\Html\Tag\Label) {
            $tag->addClass('form-label');
        }
        
        // etc etc
        
        return $tag;
    }

}
```

And somewhere in your app you do something like:

```php
$h = new MyApp\Html\Builder;
$h->addDecorator(new MyApp\Html\Decorator\Bootstrap);

// later on you will not be required to add the Bootstrap class by hand
echo $h->label(null, 'Your name');
```

## Other ideas

You can extend the library to generate documentation for a class (using reflection);

```php
$b = new MyApp\Documentation\Builder;

$b->classDocumentation(null, 'MyApp\Model\User');
```

You can extend the library to generate templates or any other type of output (I know it sounds crazy but it's not).

```php 

$b = new MyApp\Templates\Builder;

file_put_contents(APP_PATH . 'src/models/User.php', $b->modelClass(['specs' => $someClassSpecifications]);
```