<?php
namespace Sirius\Html\Tag;

class File extends Input
{

    function render()
    {
        $this->setAttribute('type', 'file');
        return parent::render();
    }
}