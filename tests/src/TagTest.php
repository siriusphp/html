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

    /*
     * @var Div
     */
    protected $element;

    function setUp()
    {
        $this->element = new Div([ ], 'Lorem ipsum...');
    }

    function testAttributeIsSet()
    {
        $this->element->set('name', 'email');
        $this->assertEquals('email', $this->element->get('name'));
    }

    function testAttributeListIsRetrieved()
    {
        $attrs = array(
            'name'  => 'email',
            'value' => 'me@domain.com',
            'id'    => 'form-email'
        );
        $this->element->setProps($attrs);
        $this->assertEquals(array(
            'name'  => 'email',
            'value' => 'me@domain.com'
        ), $this->element->getProps(array(
            'name',
            'value'
        )));
    }

    function testAllAttributesAreRetrieved()
    {
        $attrs = array(
            'name'  => 'email',
            'value' => 'me@domain.com'
        );
        $this->element->setProps($attrs);
        $this->assertEquals($attrs, $this->element->getProps());
    }

    function testAttributeIsUnset()
    {
        $attrs = array(
            'name'  => 'email',
            'value' => 'me@domain.com'
        );
        $this->element->setProps($attrs);
        $this->element->set('value', null);
        $this->assertEquals(array(
            'name' => 'email'
        ), $this->element->getProps());
    }

    function testAttributeNameIsCleaned()
    {
        $this->element->set('@name#', 'name');
        $this->assertEquals(array(
            'name' => 'name'
        ), $this->element->getProps());
    }

    function testAddClass()
    {
        $this->element->addClass('active');
        $this->assertEquals('active', $this->element->get('class'));

        $this->element->addClass('disabled');
        $this->assertEquals('active disabled', $this->element->get('class'));
    }

    function testHasClass()
    {
        $this->element->set('class', 'active disabled even');
        $this->assertTrue($this->element->hasClass('active'));
        $this->assertTrue($this->element->hasClass('disabled'));
        $this->assertTrue($this->element->hasClass('even'));
        $this->assertFalse($this->element->hasClass('xdisabled'));
    }

    function testRemoveClass()
    {
        $this->element->set('class', 'active disabled even');
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
        $this->assertTrue(strpos($this->element->render(),
                '<div class="wrapper"><div class="container">Lorem ipsum...</div>') !== false);
        $this->assertTrue(strpos($this->element->render(), '</div><i class="icon-date"></i>') !== false);
    }

    function testAppend()
    {
        $this->element->clearContent();
        $this->element->append('<article>Article</article>');
        $this->element->append('<aside>Aside</aside>');
        $this->assertTrue(strpos($this->element->getInnerHtml(),
                '<article') < strpos($this->element->getInnerHtml(), '<aside'));
    }

    function testPrepend()
    {
        $this->element->clearContent();
        $this->element->prepend('<article>Article</article>');
        $this->element->prepend('<aside>Aside</aside>');
        $this->assertTrue(strpos($this->element->getInnerHtml(),
                '<article') > strpos($this->element->getInnerHtml(), '<aside'));
    }

    function testSpecialAttributesCharacters()
    {
        $this->element->set('class', '<weird>"characters"');
        $this->assertEquals('<div class="&lt;weird&gt;&quot;characters&quot;">Lorem ipsum...</div>',
            (string) $this->element);
    }

    function testEmptyAttribute()
    {
        $this->element->set('class', '');
        $this->assertEquals('<div class="">Lorem ipsum...</div>', (string) $this->element);
    }

    function testNonStringAttribute() {
        $this->element->set('rows', 5);
        $this->assertEquals('<div rows="5">Lorem ipsum...</div>', (string) $this->element);
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

        $h1 = Tag::create('h1', [ ], 'Title content');
        $this->assertEquals('<h1>Title content</h1>', $h1->render());
    }
}
