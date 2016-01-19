<?php
namespace Sirius\Html\Tag;

class Text extends Input
{

    public function render()
    {
        $this->set('value', $this->getValue());

        return parent::render();
    }
}
