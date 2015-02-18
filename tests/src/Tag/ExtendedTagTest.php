<?php
namespace Sirius\FormsTest\Html;

use Sirius\Html\Tag\ExtendedTag as Tag;

class Div extends Tag
{

    protected $tag = 'div';

    protected $isSelfClosing = false;
}

class Hr extends Tag
{

    protected $tag = 'hr';

    protected $isSelfClosing = true;
}

class DivTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Div(array(
            'class' => 'container'
        ), 'Lorem ipsum...');
    }

    function testRender()
    {
        $this->assertEquals('<div class="container">Lorem ipsum...</div>', (string) $this->input);
        $this->assertEquals('<div class="container">Lorem ipsum...</div>', $this->input->render());
    }

    function testWrap()
    {
        $this->input->after('<i class="icon-date"></i>');
        $this->input->wrap('<div class="wrapper">', '</div>');
        $this->assertTrue(strpos($this->input->render(), '<div class="wrapper"><div class="container">Lorem ipsum...</div>') !== false);
        $this->assertTrue(strpos($this->input->render(), '</div><i class="icon-date"></i>') !== false);
    }

    function testSelfClosingTag()
    {
        $this->assertEquals('<hr class="separator">', new Hr(array(
            'class' => 'separator'
        )));
    }
}
