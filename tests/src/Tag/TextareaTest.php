<?php
namespace Sirius\Html\Tag;

class TextareaTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Textarea(array(
            'name' => 'comment',
            'cols' => '30'
        ), 'Sirius HTML rocks!');
    }

    function testRender()
    {
        $this->assertEquals('<textarea cols="30" name="comment">Sirius HTML rocks!</textarea>',
            (string) $this->input);
    }
}
