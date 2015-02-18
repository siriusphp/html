<?php
namespace Sirius\Html\Tag;

class Hidden extends Input
{

    function render()
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('value', $this->getValue());
        return parent::render();
    }
}