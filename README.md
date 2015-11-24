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

## Links

- [documentation](http://www.sirius.ro/php/sirius/html)
- [changelog](CHANGELOG.md)

