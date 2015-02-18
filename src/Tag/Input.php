<?php
namespace Sirius\Html\Tag;

/**
 * Base class for input elements.
 * Besides a regular HTML element input elements have a name and a value.
 *
 * @see \Sirius\FormsRenderer\Renderer\Widget\Base
 */
class Input extends ExtendedTag
{

    protected $tag = 'input';

    protected $isSelfClosing = true;

    /**
     * Value of the input field
     *
     * @var mixed
     */
    protected $value;

    function __construct($attr = array(), $content = null, $data = array())
    {
        parent::__construct($attr, $content, $data);
        if (isset($data['value'])) {
            $this->setValue($data['value']);
        }
    }

    /**
     * Set value of the input element
     *
     * @param string $val
     * @return self
     */
    function setValue($val)
    {
        $this->value = $val;
        return $this;
    }

    /**
     * Get value of the input element
     *
     * @return string
     */
    function getValue()
    {
        return $this->value;
    }
}
