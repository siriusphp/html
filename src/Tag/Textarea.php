<?php
namespace Sirius\Html\Tag;

class Textarea extends Input
{
    protected $tag = 'textarea';

    protected $isSelfClosing = false;

    public function getInnerHtml()
    {
        return $this->getValue();
    }
}
