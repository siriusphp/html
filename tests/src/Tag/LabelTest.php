<?php
namespace Sirius\Html\Tag;

class LabelTest extends \PHPUnit_Framework_TestCase {

    function setUp() {
        $this->input = new Label( 'Email', array(
            'for' => 'email'
        ) );
    }

    function testRender() {
        $this->assertEquals( '<label for="email">Email</label>', (string) $this->input );
    }
}
