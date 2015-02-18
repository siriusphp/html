<?php
namespace Sirius\Html\Tag;

class Hidden extends Input
{

    public function render()
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('value', $this->getValue());
        return parent::render();
    }
}