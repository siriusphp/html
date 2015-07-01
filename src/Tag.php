<?php
namespace Sirius\Html;

/**
 * Extended class for building HTML elements.
 *
 * It offers an interface similar to jQuery's DOM handling functionality
 * (besides the Input's class functionality)
 * - before(): add something before the element
 * - after(): add something after the element;
 */
class Tag
{

    /**
     * Items (strings) to be added before the element
     *
     * @var array
     */
    protected $before = array();

    /**
     * Items (strings) to be added after the element
     *
     * @var array
     */
    protected $after = array();

    /**
     * The HTML tag
     *
     * @var string
     */
    protected $tag = 'div';

    /**
     * Is the element self enclosing
     *
     * @var bool
     */
    protected $isSelfClosing = false;

    /**
     * Properties collection
     *
     * @var array
     */
    protected $props = array();

    /**
     * Content of the element.
     * Can be a string, array, object that has __toString()
     *
     * @var mixed
     */
    protected $content = array();

    /**
     * Parent element
     *
     * @var Tag
     */
    protected $parent;

    /**
     * The tag builder.
     * This is so we can attach children without having to construct them
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Factory method.
     * If $tag ends in '/' the tag will be considered 'self-closing'
     *
     * @example ExtendedTag::factory('hr/', null, ['class' => 'separator']);
     *          ExtendedTag::factory('div', 'This is my content', ['class' => 'container']);
     *
     * @param string $tag
     * @param array $props
     * @param mixed $content
     * @param Builder $builder
     *
     * @return Tag
     */
    static public function create($tag, $props = null, $content = null, Builder $builder = null)
    {
        $widget = new static($props, $content, $builder);
        if (substr($tag, - 1) === '/') {
            $widget->tag           = substr($tag, 0, - 1);
            $widget->isSelfClosing = true;
        } else {
            $widget->tag           = $tag;
            $widget->isSelfClosing = false;
        }
        $widget->setContent($content);

        return $widget;
    }

    /**
     *
     * @param mixed $content
     *            Content of the HTML element (a string, an array)
     * @param array $props
     *            Additional data for the HTML element (attributes, private data)
     */
    public function __construct($props = null, $content = null, Builder $builder = null)
    {
        $this->builder = $builder;
        if ($props !== null) {
            $this->setProps($props);
        }
        if ($content !== null) {
            $this->setContent($content);
        }
    }

    /**
     * Set multipe properties to the HTML element
     *
     * @param array $props
     *
     * @return self
     */
    public function setProps($props)
    {
        if ( ! is_array($props)) {
            return $this;
        }
        foreach ($props as $name => $value) {
            $this->set($name, $value);
        }

        return $this;
    }

    /**
     * Set a single property to the HTML element
     *
     * @param string $name
     * @param mixed $value
     *
     * @return Tag
     */
    public function set($name, $value = null)
    {
        if (is_string($name)) {
            $name = $this->cleanAttributeName($name);
            if ($value === null && isset($this->props[$name])) {
                unset($this->props[$name]);
            } elseif ($value !== null) {
                $this->props[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    protected function cleanAttributeName($name)
    {
        // private attributes are allowed to have any form
        if (substr($name, 0, 1) === '_') {
            return $name;
        }

        return preg_replace('/[^a-zA-Z0-9-]+/', '', $name);
    }

    /**
     * Returns some or all of the HTML element's properties
     *
     * @param array|null $list
     *
     * @return array
     */
    public function getProps($list = null)
    {
        if ($list && is_array($list)) {
            $result = array();
            foreach ($list as $key) {
                $result[$key] = $this->get($key);
            }

            return $result;
        }

        return $this->props;
    }

    /**
     * Returns one of HTML element's properties
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        $name = $this->cleanAttributeName($name);

        return isset($this->props[$name]) ? $this->props[$name] : null;
    }

    /**
     * Add a class to the element's class list
     *
     * @param string $class
     *
     * @return self
     */
    public function addClass($class)
    {
        if ( ! $this->hasClass($class)) {
            $this->set('class', trim((string) $this->get('class') . ' ' . $class));
        }

        return $this;
    }

    /**
     * Remove a class from the element's class list
     *
     * @param string $class
     *
     * @return self
     */
    public function removeClass($class)
    {
        $classes = $this->get('class');
        if ($classes) {
            $classes = trim(preg_replace('/(^| ){1}' . $class . '( |$){1}/i', ' ', $classes));
            $this->set('class', $classes);
        }

        return $this;
    }

    /**
     * Toggles a class on the element
     *
     * @param string $class
     *
     * @return self
     */
    public function toggleClass($class)
    {
        if ($this->hasClass($class)) {
            return $this->removeClass($class);
        }

        return $this->addClass($class);
    }

    /**
     * Checks if the element has a specific class
     *
     * @param string $class
     *
     * @return boolean
     */
    public function hasClass($class)
    {
        $classes = $this->get('class');

        return $classes && ((bool) preg_match('/(^| ){1}' . $class . '( |$){1}/i', $classes));
    }

    /**
     * Set the content
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        if ( ! $content) {
            return $this;
        }
        if ( ! is_array($content)) {
            $content = array( $content );
        }
        $this->clearContent();
        foreach ($content as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Get the content/children
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Empties the content of the tag
     *
     * @return \Sirius\Html\Tag
     */
    public function clearContent()
    {
        $this->content = array();

        return $this;
    }

    /**
     * @param $tagTextOrArray
     *
     * @return self
     */
    protected function addChild($tagTextOrArray)
    {
        // a list of arguments to be passed to builder->make()
        if (is_array($tagTextOrArray) && ! empty($tagTextOrArray)) {

            if ( ! isset($this->builder)) {
                throw new \InvalidArgumentException(sprintf('Builder not attached to tag `%s`', $this->tag));
            }

            $tagName        = $tagTextOrArray[0];
            $props          = isset($tagTextOrArray[1]) ? $tagTextOrArray[1] : [ ];
            $content        = isset($tagTextOrArray[2]) ? $tagTextOrArray[2] : [ ];
            $data           = isset($tagTextOrArray[3]) ? $tagTextOrArray[3] : [ ];
            $tagTextOrArray = $this->builder->make($tagName, $props, $content, $data, $this->builder);
        }

        if ($tagTextOrArray instanceof Tag || is_string($tagTextOrArray)) {
            array_push($this->content, $tagTextOrArray);
        }

        return $this;
    }

    /**
     * Return the attributes as a string for HTML output
     * example: title="Click here to delete" class="remove"
     *
     * @return string
     */
    protected function getAttributesString()
    {
        $result = array();
        $props  = $this->getProps();
        ksort($props);
        foreach ($props as $k => $v) {
            if (substr($k, 0, 1) === '_') {
                continue;
            }
            if ($v !== true && is_string($v)) {
                $result[] = $k . '="' . $this->escapeAttr($v) . '"';
            } elseif ($v === true) {
                $result[] = $k;
            }
        }
        $props = implode(' ', $result);
        if ($props) {
            $props = ' ' . $props;
        }

        return $props;
    }

    /**
     * @param $attr
     *
     * @return string
     */
    protected function escapeAttr($attr)
    {
        $attr = (string) $attr;

        if (0 === strlen($attr)) {
            return '';
        }

        // Don't bother if there are no specialchars - saves some processing
        if ( ! preg_match('/[&<>"\']/', $attr)) {
            return $attr;
        }

        return htmlspecialchars($attr, ENT_COMPAT);
    }

    /**
     * Render the element
     *
     * @return string
     */
    public function render()
    {
        if ($this->isSelfClosing) {
            $template = "<{$this->tag}%s>";
            $element  = sprintf($template, $this->getAttributesString());
        } else {
            $template = "<{$this->tag}%s>%s</{$this->tag}>";
            $element  = sprintf($template, $this->getAttributesString(), $this->getInnerHtml());
        }
        $before = implode(PHP_EOL, $this->before);
        $after  = implode(PHP_EOL, $this->after);

        return $before . $element . $after;
    }

    /**
     * Return the innerHTML content of the tag
     *
     * @return string
     */
    public function getInnerHtml()
    {
        return implode(PHP_EOL, $this->content);
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Add a string or a stringifiable object immediately before the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function before($stringOrObject)
    {
        array_unshift($this->before, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object immediately after the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function after($stringOrObject)
    {
        array_push($this->after, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object immediately as the first child of the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function prepend($stringOrObject)
    {
        array_unshift($this->content, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object as the last child the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function append($stringOrObject)
    {
        array_push($this->content, $stringOrObject);

        return $this;
    }

    /**
     * Add something before and after the element.
     * Proxy for calling before() and after() simoultaneously
     *
     * @param string|object $before
     * @param string|object $after
     *
     * @return Tag
     */
    public function wrap($before, $after)
    {
        return $this->before($before)->after($after);
    }
}

