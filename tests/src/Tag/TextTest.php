<?php
namespace Sirius\Html\Tag;

use Sirius\Html\TagText;

class TextTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Text(array(
            'name' => 'username',
            'disabled' => true,
            'class' => 'not-valid'
        ), null, array(
            'value' => 'siriusforms'
        ));
    }

    function testAttributes()
    {
        $this->assertEquals('siriusforms', $this->input->getValue());
        $this->assertEquals('username', $this->input->getAttribute('name'));
        $this->assertTrue($this->input->hasClass('not-valid'));
    }

    function testRender()
    {
        $this->assertEquals('<input class="not-valid" disabled name="username" value="siriusforms">', (string) $this->input);
    }
}
