<?php
namespace Sirius\Html\Tag;

class PasswordTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Password(array(
            'name'  => 'password',
            'class' => 'not-valid'
        ), '0123456');
    }

    function testRender()
    {
        $this->assertEquals('<input class="not-valid" name="password" type="password">', (string) $this->input);
    }
}
