<?php
namespace Sirius\Html\Tag;

/**
 * Base class for building HTML elements
 *
 * - attr(): set/get the element's attributes
 * - text(): set/get the element's innerHTML
 * - addClass(), removeClass(), toggleClass(): manipulate the element's classes
 * - data(): set/get miscelaneous data to the element
 */
class BaseTag
{
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
     * Attributes collection
     *
     * @var array
     */
    protected $attrs = array();

    /**
     * Data attached to the element (think jQuery)
     *
     * @var array
     */
    protected $data = array();

    /**
     * Factory method.
     * If $tag ends in '/' the tag will be considered 'self-closing'
     *
     * @example BaseTag::factory('hr/', ['class' => 'separator']);
     *          BaseTag::factory('div', ['class' => 'container'], 'This is my content');
     *         
     * @param string $tag            
     * @param array $attr            
     * @param mixed $content            
     * @param array $data            
     * @return \Sirius\Html\Tag\Tag
     */
    static function create($tag, $attr = array(), $content = null, $data = array())
    {
        $widget = new static($attr, $content, $data);
        if (substr($tag, - 1) === '/') {
            $widget->tag = substr($tag, 0, - 1);
            $widget->isSelfClosing = true;
        } else {
            $widget->tag = $tag;
            $widget->isSelfClosing = false;
        }
        return $widget;
    }

    /**
     * Content of the element.
     * Can be a string, array, object that has __toString()
     *
     * @var mixed
     */
    protected $content;

    /**
     *
     * @param array $attrs
     *            Attributes of the HTML tag
     * @param string $content
     *            Content of the HTML element (a string, an array)
     * @param array $data
     *            Additional data for the HTML element
     */
    function __construct($attrs = array(), $content = null, $data = array())
    {
        if ($attrs) {
            $this->setAttributes($attrs);
        }
        if ($content) {
            $this->setContent($content);
        }
        if ($data) {
            $this->setData($data);
        }
    }

    /**
     * Set multipe attributes to the HTML element
     *
     * @param
     *            $attrs
     * @return self
     */
    function setAttributes($attrs)
    {
        foreach ($attrs as $name => $value) {
            $this->setAttribute($name, $value);
        }
        return $this;
    }

    /**
     * Set a single attribute to the HTML element
     *
     * @param
     *            $name
     * @param null $value            
     * @return $this
     */
    function setAttribute($name, $value = null)
    {
        if (is_string($name)) {
            if ($value === null && isset($this->attrs[$name])) {
                unset($this->attrs[$name]);
            } elseif ($value !== null) {
                $this->attrs[$name] = $value;
            }
        }
        return $this;
    }

    /**
     * Returns some or all of the HTML element's attributes
     *
     * @param null|array $list            
     * @return array
     */
    function getAttributes($list = null)
    {
        if ($list && is_array($list)) {
            $result = array();
            foreach ($list as $key) {
                $result[$key] = $this->getAttribute($key);
            }
            return $result;
        }
        return $this->attrs;
    }

    /**
     * Returns one of HTML element's attributes
     *
     * @param
     *            $name
     * @return null
     */
    function getAttribute($name)
    {
        return isset($this->attrs[$name]) ? $this->attrs[$name] : null;
    }

    /**
     * Add a class to the element's class list
     *
     * @param string $class            
     * @return self
     */
    function addClass($class)
    {
        if (! $this->hasClass($class)) {
            $this->setAttribute('class', trim((string) $this->getAttribute('class') . ' ' . $class));
        }
        return $this;
    }

    /**
     * Remove a class from the element's class list
     *
     * @param string $class            
     * @return self
     */
    function removeClass($class)
    {
        $classes = $this->getAttribute('class');
        if ($classes) {
            $classes = preg_replace('/(^| ){1}' . $class . '( |$){1}/i', ' ', $classes);
            $this->setAttribute('class', $classes);
        }
        return $this;
    }

    /**
     * Toggles a class on the element
     *
     * @param string $class            
     * @return self
     */
    function toggleClass($class)
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
     * @return boolean
     */
    function hasClass($class)
    {
        $classes = $this->getAttribute('class');
        return $classes && ((bool) preg_match('/(^| ){1}' . $class . '( |$){1}/i', $classes));
    }

    /**
     * Set the content
     *
     * @param
     *            $content
     * @return $this
     */
    function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the content/children
     *
     * @return mixed
     */
    function getContent()
    {
        return $this->content;
    }

    /**
     * Get one, more or all of the additional data attached to the HTML element
     *
     * @param null $name            
     * @return array
     */
    function getData($name = null)
    {
        if (is_string($name)) {
            if (isset($this->data[$name])) {
                return $this->data[$name];
            } else {
                return null;
            }
        } elseif (is_array($name)) {
            $data = array();
            foreach ($name as $k) {
                if (isset($this->data[$k])) {
                    $data[$k] = $this->data[$k];
                } else {
                    $data[$k] = null;
                }
            }
            return $data;
        }
        return $this->data;
    }

    /**
     * Set one or more data items on the HTML element
     *
     * @param null $name            
     * @param null $value            
     * @return $this
     */
    function setData($name = null, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->data[$k] = $v;
            }
        } elseif (is_string($name)) {
            if ($value === null && isset($this->data[$name])) {
                unset($this->data[$name]);
            } elseif ($value !== null) {
                $this->data[$name] = $value;
            }
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
        $attrs = $this->getAttributes();
        ksort($attrs);
        foreach ($attrs as $k => $v) {
            if ($v !== true && is_string($v)) {
                $result[] = $k . '="' . htmlspecialchars((string) $v, ENT_COMPAT) . '"';
            } elseif ($v === true) {
                $result[] = $k;
            }
        }
        $attrs = implode(' ', $result);
        if ($attrs) {
            $attrs = ' ' . $attrs;
        }
        return $attrs;
    }

    /**
     * Render the element
     *
     * @return string
     */
    function render()
    {
        if ($this->isSelfClosing) {
            $template = "<{$this->tag}%s>";
            $element = sprintf($template, $this->getAttributesString());
        } else {
            $template = "<{$this->tag}%s>%s</{$this->tag}>";
            $element = sprintf($template, $this->getAttributesString(), $this->getInnerHtml());
        }
        return $element;
    }

    protected function getInnerHtml()
    {
        if (is_array($this->content)) {
            return implode(PHP_EOL, $this->content);
        }
        return (string) $this->content;
    }

    function __toString()
    {
        return $this->render();
    }
}
