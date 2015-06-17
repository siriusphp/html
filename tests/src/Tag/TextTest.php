<?php
namespace Sirius\Html\Tag;

class TextTest extends \PHPUnit_Framework_TestCase {

    function setUp() {
        $this->input = new Text( 'siriusforms', array(
            'name'     => 'username',
            'disabled' => true,
            'class'    => 'not-valid'
        ) );
    }

    function testAttributes() {
        $this->assertEquals( 'siriusforms', $this->input->getValue() );
        $this->assertEquals( 'username', $this->input->get( 'name' ) );
        $this->assertTrue( $this->input->hasClass( 'not-valid' ) );
    }

    function testRender() {
        $this->assertEquals( '<input class="not-valid" disabled name="username" value="siriusforms">',
            (string) $this->input );
    }
}
