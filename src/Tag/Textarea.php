<?php

namespace Sirius\Html\Tag;

class Textarea extends Input
{
    protected string $tag = 'textarea';

    protected bool $isSelfClosing = false;

    public function getInnerHtml(): string
    {
        return $this->getValue(); // @phpstan-ignore-line
    }
}
