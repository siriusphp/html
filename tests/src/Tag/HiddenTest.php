<?php
namespace Sirius\Html\Tag;

class HiddenTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Hidden(array(
            'name' => 'token'
        ), '123');
    }

    function testRender()
    {
        $this->assertEquals('<input name="token" type="hidden" value="123">', (string) $this->input);
    }
}
