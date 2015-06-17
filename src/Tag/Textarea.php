<?php
namespace Sirius\Html\Tag;

class Textarea extends Input {

    protected $tag = 'textarea';

    protected $isSelfClosing = false;

    public function render() {
        $this->setContent( $this->getValue() );

        return parent::render();
    }
}
