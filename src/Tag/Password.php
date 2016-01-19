<?php
namespace Sirius\Html\Tag;

class Password extends Text
{

    public function render()
    {
        $this->setValue(null); // ensure the value is not displayed
        $this->set('type', 'password');

        return parent::render();
    }
}
