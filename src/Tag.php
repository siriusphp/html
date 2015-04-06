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
     * Attributes collection
     *
     * @var array
     */
    protected $attrs = array();

    /**
     * Data attached to the element (think jQuery.data() )
     *
     * @var array
     */
    protected $data = array();

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
     * @example ExtendedTag::factory('hr/', ['class' => 'separator']);
     *          ExtendedTag::factory('div', ['class' => 'container'], 'This is my content');
     *         
     * @param string $tag            
     * @param array $attr            
     * @param mixed $content            
     * @param array $data            
     * @return Tag
     */
    static function create($tag, $attr = null, $content = null, $data = null, Builder $builder = null)
    {
        $widget = new static($attr, null, $data, $builder);
        if (substr($tag, - 1) === '/') {
            $widget->tag = substr($tag, 0, - 1);
            $widget->isSelfClosing = true;
        } else {
            $widget->tag = $tag;
            $widget->isSelfClosing = false;
        }
        $widget->setContent($content);
        return $widget;
    }

    /**
     *
     * @param array $attrs
     *            Attributes of the HTML tag
     * @param string $content
     *            Content of the HTML element (a string, an array)
     * @param array $data
     *            Additional data for the HTML element
     */
    public function __construct($attrs = null, $content = null, $data = null, Builder $builder = null)
    {
        $this->builder = $builder;
        if ($attrs !== null) {
            $this->setAttributes($attrs);
        }
        if ($content !== null) {
            $this->setContent($content);
        }
        if ($data !== null) {
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
    public function setAttributes($attrs)
    {
        foreach ($attrs as $name => $value) {
            $this->setAttribute($name, $value);
        }
        return $this;
    }

    /**
     * Set a single attribute to the HTML element
     *
     * @param string $name
     * @param mixed $value            
     * @return Tag
     */
    public function setAttribute($name, $value = null)
    {
        if (is_string($name)) {
            $name = $this->cleanAttributeName($name);
            if ($value === null && isset($this->attrs[$name])) {
                unset($this->attrs[$name]);
            } elseif ($value !== null) {
                $this->attrs[$name] = $value;
            }
        }
        return $this;
    }
    
    protected function cleanAttributeName($name) {
        return preg_replace('/[^a-zA-Z0-9-]+/', '', $name);
    }

    /**
     * Returns some or all of the HTML element's attributes
     *
     * @param array|null $list            
     * @return array
     */
    public function getAttributes($list = null)
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
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        return isset($this->attrs[$name]) ? $this->attrs[$name] : null;
    }

    /**
     * Add a class to the element's class list
     *
     * @param string $class            
     * @return self
     */
    public function addClass($class)
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
    public function removeClass($class)
    {
        $classes = $this->getAttribute('class');
        if ($classes) {
            $classes = trim(preg_replace('/(^| ){1}' . $class . '( |$){1}/i', ' ', $classes));
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
     * @return boolean
     */
    public function hasClass($class)
    {
        $classes = $this->getAttribute('class');
        return $classes && ((bool) preg_match('/(^| ){1}' . $class . '( |$){1}/i', $classes));
    }

    /**
     * Set the content
     *
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        if (!$content) {
            return $this;
        }
        if (!is_array($content)) {
            $content = array($content);
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
     * @return \Sirius\Html\TagContainer
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
    function clearContent() {
        $this->content = array();
        return $this;
    }
    
    protected function addChild($tagTextOrArray) {
        // a text node
        if (is_string($tagTextOrArray)) {
            return array_push($this->content, $tagTextOrArray);            
        }
        
        // an already constructed tag
        if ($tagTextOrArray instanceof Tag) {
            return array_push($this->content, $tagTextOrArray);
        }
        
        if (!isset($this->builder)) {
            throw new \InvalidArgumentException(sprintf('Builder not attached to tag `%s`', $this->tag));
        }
        
        if (is_array($tagTextOrArray) && !empty($tagTextOrArray)) {
            $tagName = $tagTextOrArray[0];
            $attrs = isset($tagTextOrArray[1]) ? $tagTextOrArray[1] : [];
            $content = isset($tagTextOrArray[2]) ? $tagTextOrArray[2] : [];
            $data = isset($tagTextOrArray[3]) ? $tagTextOrArray[3] : [];            
            $tag = $this->builder->make($tagName, $attrs, $content, $data, $this->builder);
            return array_push($this->content, $tag);
        }
    }

    /**
     * Get one, more or all of the additional data attached to the HTML element
     *
     * @param string|array|null $name            
     * @return array
     */
    public function getData($name = null)
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
     * @param string|array $name            
     * @param mixed $value            
     * @return $this
     */
    public function setData($name = null, $value = null)
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
    public function render()
    {
        if ($this->isSelfClosing) {
            $template = "<{$this->tag}%s>";
            $element = sprintf($template, $this->getAttributesString());
        } else {
            $template = "<{$this->tag}%s>%s</{$this->tag}>";
            $element = sprintf($template, $this->getAttributesString(), $this->getInnerHtml());
        }
        $before = implode(PHP_EOL, $this->before);
        $after = implode(PHP_EOL, $this->after);
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
     * @return Tag
     */
    public function wrap($before, $after)
    {
        return $this->before($before)->after($after);
    }
}

