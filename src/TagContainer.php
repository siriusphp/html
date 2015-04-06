<?php
namespace Sirius\Html;

class TagContainer extends \ArrayObject {
    
    function append($value) {
        parent::append($value);
        return $this;
    }
    
    function prepend($value) {
        $array = $this->getArrayCopy();
        array_unshift($array, $value);
        $this->exchangeArray($array);
        return $this;
    }
    
    function __toString() {
        return implode(PHP_EOL, $this->getArrayCopy());
    }
}