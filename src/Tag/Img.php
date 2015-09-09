<?php
namespace Sirius\Html\Tag;

use Sirius\Html\Tag;

class Img extends Tag
{
    protected $tag = 'img';

    protected $isSelfClosing = true;

    function setContent($content)
    {
        return $this->set('src', $content);
    }
}