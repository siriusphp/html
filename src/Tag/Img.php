<?php

namespace Sirius\Html\Tag;

use Sirius\Html\Tag;

class Img extends Tag
{
    protected string $tag = 'img';

    protected bool $isSelfClosing = true;

    public function setContent(mixed $content): static
    {
        if (empty($content)) {
            return $this;
        }
        return $this->set('src', $content);
    }
}
