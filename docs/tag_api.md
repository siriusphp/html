
# Tag API

Once you get access to a `Tag` object you can do stuff with it:

##### `getProps($list)` | `setProps($props)`
$list = the names of the properties to be retrieved (null = ALL attributes)
$props = associative array containing the attributes and private data

##### `get($name)` | `set($name, $value)`
Get/Set a property

##### `addClass($class)` | `removeClass($class)` | `toggleClass($class)`
Utility methods to work with the `class` attribute

##### `getValue()` | `setValue($value)`
Available only for elements that extend `Sirius\Html\Tag\Input`

For form elements. They are aliases for `get('_value')` and `set('_value', $value)`

##### `getContent()` | `setContent($content)`
$content can be a string or an array. `getContent` returns an array that you can play with

##### `append($stringOrObject)` | `prepend($stringOrObject)`
To add something inside the tag at the beginning (`prepend`) or at the end (`append`)

##### `before($stringOrObject)` | `after($stringOrObject)`
To add something before or after the tag

##### `wrap($before, $after)`
So you don't have to call `before` and `after`