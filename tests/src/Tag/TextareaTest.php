<?php
namespace Sirius\Html\Tag;

class TextareaTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Textarea(array(
            'name' => 'comment',
            'cols' => '30'
        ), 'Sirius Forms rocks!');
    }

    function testRender()
    {
        $this->assertEquals('<textarea cols="30" name="comment">Sirius Forms rocks!</textarea>', (string) $this->input);
    }
}
