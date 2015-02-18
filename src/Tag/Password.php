<?php
namespace Sirius\Html\Tag;

class Password extends Text
{

    protected $tag = 'input';

    protected $isSelfClosing = true;

    function render()
    {
        $this->setValue(null); // ensure the value is not displayed
        $this->setAttribute('type', 'password');
        return parent::render();
    }
}
