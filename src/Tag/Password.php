<?php
namespace Sirius\Html\Tag;

class Password extends Text
{

    protected $tag = 'input';

    protected $isSelfClosing = true;

    public function render()
    {
        $this->setValue(null); // ensure the value is not displayed
        $this->set('type', 'password');

        return parent::render();
    }
}
