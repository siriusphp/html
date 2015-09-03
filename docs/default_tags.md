---
title: Default tags
---

# The default HTML tags

By default the HTML builder instance knows to render any tag you throw at it.

```php

echo $h->asdfghij();

// will output <asdfghij></asdfghij>
```

Because the form input tags behave differently the library comes packaged with a few tags that have their own classes:

- `input`: the default input tag 
- `text`: extends the `input` tag and has the `type` attribute default to `text`
- `radio` and `checkbox`: an `input` with the `type` attribute set to `radio` and gets checked if the content is equal to the `value` attribute
- `password`
- `textarea`
- `file`
- `hidden`
- `select` and `multiselect` (a `select` with the attribute `multiple`)
- `label`
- `button`
- `img`: this has its own class because it's a self-closing tag and it is very used

This allows you to easily create form elements like so

```
$h->radio(["name" => "true_or_false", "value" => 'true'], 'false');
$h->radio(["name" => "true_or_false", "value" => 'false'], 'false');

$h->select(["name" => "country", "_options" => $contriesList"], $_POST['country']);

$h->img(["src" => "http://whatever.com/img.png"]);
```