<?php
namespace Sirius\Html\Tag;

class LabelTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Label(array(
            'for' => 'email'
        ), 'Email');
    }

    function testRender()
    {
        $this->assertEquals('<label for="email">Email</label>', (string) $this->input);
    }
}
