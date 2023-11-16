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
    protected string $tag = 'input';

    protected bool $isSelfClosing = true;

    public function setValue(mixed $val): static
    {
        return $this->set('_value', $val);
    }

    public function getValue(): mixed
    {
        return $this->get('_value');
    }

    public function setContent(mixed $content): static
    {
        return $this->setValue($content);
    }

    public function getContent(): mixed
    {
        return $this->getValue();
    }
}
