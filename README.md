# Sirius HTML

[![Source Code](http://img.shields.io/badge/source-siriusphp/html-blue.svg?style=flat-square)](https://github.com/siriusphp/html)
[![Latest Version](https://img.shields.io/packagist/v/siriusphp/html.svg?style=flat-square)](https://github.com/siriusphp/html/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/siriusphp/html/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/siriusphp/html/master.svg?style=flat-square)](https://travis-ci.org/siriusphp/html)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/siriusphp/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/html/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/siriusphp/html.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/html)

Framework agnostic HTML rendering utility with an API inspired by jQuery and React.

## Elevator pitch

```php
$h = new Sirius\Html\Builder;

$h->registerTag('user-login-form', 'MyApp\Html\Components\UserLoginForm');

echo $h->make('user-login-form', ['_form_values' => $_POST]);
```

which will output something like

```html
<form method="post" action="user/login" class="form form-inline">

<div class="form-control">
    <label for="email">Email/Username:</label>
    <input type="email" name="email" id="email" value="me@domain.com">
</div>

<!-- the rest of the form goes here -->

</form>
```

### Performance

There is a simple [benchmark](tests/benchmark/) that renders a form with 4 fields and a button. Compared to Twig, Sirius HTML is 50% slower but uses 2x less memory.

The benchmark results are as follows for 100K iterations:

| Library     |  Time | Memory |
|-------------|------:|-------:|
| Sirius HTML | 1.78s |    2Mb |
| Twig        | 1.14s |    4Mb |

Of course, this is not a real world scenario, but it gives you an idea of what to expect from this library.


## Links

- [documentation](http://sirius.ro/php/sirius/html)

