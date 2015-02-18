<?php
namespace Sirius\Html;

class Builder {
    
    protected $tags = array(
    	'button' => 'Sirius\Html\Tag\Button',
    	'checkbox' => 'Sirius\Html\Tag\Checkbox',
        'div' => 'Sirius\Html\Tag\Div',
    	'file' => 'Sirius\Html\Tag\File',
    	'hidden' => 'Sirius\Html\Tag\hidden',
        'multiselect' => 'Sirius\Html\Tag\MultiSelect',
        'p' => 'Sirius\Html\Tag\Paragraph',
    	'paragraph' => 'Sirius\Html\Tag\Paragraph',
    	'password' => 'Sirius\Html\Tag\Password',
    	'radio' => 'Sirius\Html\Tag\Radio',
    	'select' => 'Sirius\Html\Tag\Select',
        'text' => 'Sirius\Html\Tag\Text',
    	'textarea' => 'Sirius\Html\Tag\Textarea',
    );
    
    function addElement($name, $classOrCallback)
    {
        $this->tags[$name] = $classOrCallback;
    }
    
    function make($name, $attrs = array(), $content = null, $data = null) {
        if (!isset($this->tags[$name])) {
            return Tag\ExtendedTag::create($name, $attrs, $content, $data);
        }

        $constructor = $this->tags[$name];
        if (is_callable()) {
            return call_user_func($constructor, $attrs, $content, $data);
        }
        
        if (class_exists($constructor)) {
            return new $constructor($attrs, $content, $data);
        }
        
        throw new \InvalidArgumentException(sprintf('Invalid constructor for the `%s` tag', $name));
    }
    
    function __call($method, $args) {
        $method = preg_replace('/[A-Z]+/', '-$1', $method);
        $method = strtolower($method);
        if (!isset($args[0])) {
            $args[0] = array();
        }
        if (!isset($args[1])) {
            $args[1] = null;
        }
        if (!isset($args[2])) {
            $args[2] = null;
        }
        return $this->make($method, $args[0], $args[1], $args[2]);
    }
    
}