<?php
namespace Sirius\Html;

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
        $this->element = new Div(array(), 'Lorem ipsum...');
    }

    function testAttributeIsSet()
    {
        $this->element->setAttribute('name', 'email');
        $this->assertEquals('email', $this->element->getAttribute('name'));
    }

    function testAttributeListIsRetrieved()
    {
        $attrs = array(
            'name' => 'email',
            'value' => 'me@domain.com',
            'id' => 'form-email'
        );
        $this->element->setAttributes($attrs);
        $this->assertEquals(array(
            'name' => 'email',
            'value' => 'me@domain.com'
        ), $this->element->getAttributes(array(
            'name',
            'value'
        )));
    }

    function testAllAttributesAreRetrieved()
    {
        $attrs = array(
            'name' => 'email',
            'value' => 'me@domain.com'
        );
        $this->element->setAttributes($attrs);
        $this->assertEquals($attrs, $this->element->getAttributes());
    }

    function testAttributeIsUnset()
    {
        $attrs = array(
            'name' => 'email',
            'value' => 'me@domain.com'
        );
        $this->element->setAttributes($attrs);
        $this->element->setAttribute('value', null);
        $this->assertEquals(array(
            'name' => 'email'
        ), $this->element->getAttributes());
    }
    
    function testAttributeNameIsCleaned() {
        $this->element->setAttribute('@name#', 'name');
        $this->assertEquals(array(
        	'name' => 'name'
        ), $this->element->getAttributes());
    }

    function testAddClass()
    {
        $this->element->addClass('active');
        $this->assertEquals('active', $this->element->getAttribute('class'));
        
        $this->element->addClass('disabled');
        $this->assertEquals('active disabled', $this->element->getAttribute('class'));
    }

    function testHasClass()
    {
        $this->element->setAttribute('class', 'active disabled even');
        $this->assertTrue($this->element->hasClass('active'));
        $this->assertTrue($this->element->hasClass('disabled'));
        $this->assertTrue($this->element->hasClass('even'));
        $this->assertFalse($this->element->hasClass('xdisabled'));
    }

    function testRemoveClass()
    {
        $this->element->setAttribute('class', 'active disabled even');
        $this->element->removeClass('disabled');
        $this->assertFalse($this->element->hasClass('disabled'));
    }

    function testToggleClass()
    {
        $this->assertFalse($this->element->hasClass('active'));
        $this->element->toggleClass('active');
        $this->assertTrue($this->element->hasClass('active'));
        $this->element->toggleClass('active');
        $this->assertFalse($this->element->hasClass('active'));
    }

    function testPreviousContentIsOverwritten()
    {
        $this->element->setContent('cool');
        $this->assertEquals('cool', $this->element->getInnerHtml());
    }

    function testDataIsSet()
    {
        // no data at the begining
        $this->assertEquals(array(), $this->element->getData());
        $this->element->setData('string', 'cool');
        $this->assertEquals('cool', $this->element->getData('string'));
    }

    function testDataIsUnset()
    {
        $this->element->setData('string', 'cool');
        $this->element->setData('string', null);
        $this->assertNull($this->element->getData('string'));
    }

    function testBulkDataIsSet()
    {
        $data = array(
            'k1' => 'v1',
            'k2' => 'v2'
        );
        $this->element->setData($data);
        $this->assertEquals($data, $this->element->getData());
    }

    function testDataListIsRetrieved()
    {
        $data = array(
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3'
        );
        $this->element->setData($data);
        $this->assertEquals(array(
            'k1' => 'v1',
            'k3' => 'v3',
            'k4' => null
        ), $this->element->getData(array(
            'k1',
            'k3',
            'k4'
        )));
    }

    function testContentArray()
    {
        $this->element->setContent(array(
            'a',
            'b',
            'c'
        ));
        $this->assertEquals('<div>a' . PHP_EOL . 'b' . PHP_EOL . 'c</div>', $this->element->render());
    }

    function testRender()
    {
        $this->element->addClass('container');
        $this->assertEquals('<div class="container">Lorem ipsum...</div>', (string) $this->element);
        $this->assertEquals('<div class="container">Lorem ipsum...</div>', $this->element->render());
    }

    function testWrap()
    {
        $this->element->addClass('container');
        $this->element->after('<i class="icon-date"></i>');
        $this->element->wrap('<div class="wrapper">', '</div>');
        $this->assertTrue(strpos($this->element->render(), '<div class="wrapper"><div class="container">Lorem ipsum...</div>') !== false);
        $this->assertTrue(strpos($this->element->render(), '</div><i class="icon-date"></i>') !== false);
    }
    
    function testAppend() {
        $this->element->clearContent();
        $this->element->append('<article>Article</article>');
        $this->element->append('<aside>Aside</aside>');
        $this->assertTrue(strpos($this->element->getInnerHtml(), '<article') < strpos($this->element->getInnerHtml(), '<aside'));
    }

    function testPrepend() {
        $this->element->clearContent();
        $this->element->prepend('<article>Article</article>');
        $this->element->prepend('<aside>Aside</aside>');
        $this->assertTrue(strpos($this->element->getInnerHtml(), '<article') > strpos($this->element->getInnerHtml(), '<aside'));
    }
    
    function testSelfClosingTag()
    {
        $this->assertEquals('<hr class="separator">', new Hr(array(
            'class' => 'separator'
        )));
    }

    function testFactory()
    {
        $hr = Tag::create('hr/');
        $this->assertEquals('<hr>', $hr->render());
        
        $h1 = Tag::create('h1', null, 'Title content');
        $this->assertEquals('<h1>Title content</h1>', $h1->render());
    }
}
