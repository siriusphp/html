<?php
namespace Sirius\Html\Tag;

use Sirius\Html\TagHidden;

class HiddenTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Hidden(array(
            'name' => 'token',
        ), null, array(
        	'value' => '123'
        ));
    }

    function testRender()
    {
        $this->assertEquals('<input name="token" type="hidden" value="123">', (string)$this->input);
    }
}
