<?php

namespace Sirius\Html\Tag;

class Hidden extends Input
{
    public function render()
    {
        $this->set('type', 'hidden');
        $this->set('value', $this->getValue());

        return parent::render();
    }
}
