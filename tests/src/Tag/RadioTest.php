<?php
namespace Sirius\Html\Tag;

use Sirius\Html\TagRadio;

class RadioTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Radio(array(
            'name' => 'gender',
            'value' => 'male'
        ), null, array(
            'value' => 'male'
        ));
    }

    function testRender()
    {
        $this->assertEquals('<input checked="checked" name="gender" type="radio" value="male">', (string) $this->input);
    }
}
