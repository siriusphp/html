<?php

namespace Sirius\Html\Tag;

class Radio extends Input
{
    public function render()
    {
        $checked = $this->getValue() == $this->get('value') ? 'checked' : null;
        $this->set('checked', $checked);
        $this->set('type', 'radio');

        return parent::render();
    }
}
