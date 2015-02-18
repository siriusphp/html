<?php
namespace Sirius\Html\Tag;

class Text extends Input
{

    protected $tag = 'input';

    protected $isSelfClosing = true;

    public function render()
    {
        $this->setAttribute('value', $this->getValue());
        return parent::render();
    }
}