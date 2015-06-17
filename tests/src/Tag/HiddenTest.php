<?php
namespace Sirius\Html\Tag;

class HiddenTest extends \PHPUnit_Framework_TestCase {

    function setUp() {
        $this->input = new Hidden( '123', array(
            'name' => 'token'
        ) );
    }

    function testRender() {
        $this->assertEquals( '<input name="token" type="hidden" value="123">', (string) $this->input );
    }
}
