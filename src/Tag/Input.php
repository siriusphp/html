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

    public function __construct($content = null, $props = null)
    {
        parent::__construct(null, $props);
        $this->setValue($content);
    }

    /**
     * Set value of the input element
     *
     * @param string $val            
     * @return self
     */
    public function setValue($val)
    {
        $this->set('_value', $val);
    }

    /**
     * Get value of the input element
     *
     * @return string
     */
    public function getValue()
    {
        return $this->get('_value');
    }
}
