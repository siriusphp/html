<?php
namespace Sirius\Html\Tag;

class Checkbox extends Input
{

    function render()
    {
        $checked = null;
        if (is_array($this->getValue()) && in_array($this->getAttribute('value'), $this->getValue())) {
            $checked = 'checked';
        } elseif ($this->getAttribute('value') == $this->getValue()) {
            $checked = 'checked';
        }
        $this->setAttribute('checked', $checked);
        $this->setAttribute('type', 'checkbox');
        return parent::render();
    }
}