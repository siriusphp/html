<?php
namespace Sirius\Html\Tag;

class File extends Input
{

    public function render()
    {
        $this->setAttribute('type', 'file');
        return parent::render();
    }
}