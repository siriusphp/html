<?php
namespace Sirius\Html\Tag;

class RadioTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Radio('male', array(
            'name' => 'gender',
            'value' => 'male'
        ));
    }

    function testRender()
    {
        $this->assertEquals('<input checked="checked" name="gender" type="radio" value="male">', (string) $this->input);
    }
}
