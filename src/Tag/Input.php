<?php
namespace Sirius\Html\Tag;

use Sirius\Html\Tag;

/**
 * Base class for input elements.
 * Besides a regular HTML element input elements have a name and a value.
 *
 * @see \Sirius\FormsRenderer\Renderer\Widget\Base
 */
class Input extends Tag
{

    protected $tag = 'input';

    protected $isSelfClosing = true;

    function __construct($attr = null, $content = null, $data = null)
    {
        parent::__construct($attr, $content, $data);
        $this->setValue($content);
    }

    /**
     * Set value of the input element
     *
     * @param string $val            
     * @return self
     */
    function setValue($val)
    {
        $this->setData('value', $val);
    }

    /**
     * Get value of the input element
     *
     * @return string
     */
    function getValue()
    {
        return $this->getData('value');
    }
}
