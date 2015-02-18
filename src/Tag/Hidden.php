<?php
namespace Sirius\Html\Tag;

class Hidden extends Input
{

    function render()
    {
        $this->setAttribute('type', 'hidden');
        if ($this->value) {
            $this->setAttribute('value', $this->value);
        }
        return parent::render();
    }
}