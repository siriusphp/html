<?php
namespace Sirius\Html\Tag;

class Radio extends Input
{

    function render()
    {
        $checked = $this->getValue() == $this->getAttribute('value') ? 'checked' : null;
        $this->setAttribute('checked', $checked);
        $this->setAttribute('type', 'radio');
        return parent::render();
    }
}