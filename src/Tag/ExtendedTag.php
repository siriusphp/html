<?php
namespace Sirius\Html\Tag;

/**
 * Extended class for building HTML elements.
 *
 * It offers an interface similar to jQuery's DOM handling functionality
 * (besides the Input's class functionality)
 * - before(): add something before the element
 * - after(): add something after the element;
 */
class ExtendedTag extends BaseTag
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
     * Add a string or a stringifiable object immediately before the element
     *
     * @param string|object $stringOrObject
     * @return \Sirius\FormsRenderer\Renderer\Widget\Base
     */
    function before($stringOrObject)
    {
        array_unshift($this->before, $stringOrObject);
        return $this;
    }

    /**
     * Add a string or a stringifiable object immediately after the element
     *
     * @param string|object $stringOrObject
     * @return \Sirius\FormsRenderer\Renderer\Widget\Base
     */
    function after($stringOrObject)
    {
        array_push($this->after, $stringOrObject);
        return $this;
    }

    /**
     * Add something before and after the element.
     * Proxy for calling before() and after() simoultaneously
     *
     * @param string|object $before
     * @param string|object $after
     * @return \Sirius\FormsRenderer\Renderer\Widget\Base
     */
    function wrap($before, $after)
    {
        return $this->before($before)->after($after);
    }

    /**
     * Render the element
     *
     * @return string
     */
    function render()
    {
        $before = implode(PHP_EOL, $this->before);
        $after = implode(PHP_EOL, $this->after);
        return $before . parent::render() . $after;
    }


}

