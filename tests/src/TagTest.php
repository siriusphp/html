<?php
namespace Sirius\Html;
use \Sirius\Html\Tag;
use \Sirius\Html\Hr;
use \Sirius\Html\Div;
use Tests\TestCase;

uses(TestCase::class);

class Div extends Tag
{
    protected string $tag = 'div';

    protected bool $isSelfClosing = false;
}

class Hr extends Tag
{
    protected string $tag = 'hr';

    protected bool $isSelfClosing = true;
}

beforeEach(function () {
    $this->element = new Div(array(), 'Lorem ipsum...');
});

test('attribute is set', function () {
    $this->element->set('name', 'email');
    expect($this->element->get('name'))->toEqual('email');
});

test('attribute list is retrieved', function () {
    $attrs = array(
        'name'  => 'email',
        'value' => 'me@domain.com',
        'id'    => 'form-email'
    );
    $this->element->setProps($attrs);
    expect($this->element->getProps(array(
        'name',
        'value'
    )))->toEqual(array(
        'name'  => 'email',
        'value' => 'me@domain.com'
    ));
});

test('all attributes are retrieved', function () {
    $attrs = array(
        'name'  => 'email',
        'value' => 'me@domain.com'
    );
    $this->element->setProps($attrs);
    expect($this->element->getProps())->toEqual($attrs);
});

test('attribute is unset', function () {
    $attrs = array(
        'name'  => 'email',
        'value' => 'me@domain.com'
    );
    $this->element->setProps($attrs);
    $this->element->set('value', null);
    expect($this->element->getProps())->toEqual(array(
        'name' => 'email'
    ));
});

test('attribute name is cleaned', function () {
    $this->element->set('@name#', 'name');
    expect($this->element->getProps())->toEqual(array(
        'name' => 'name'
    ));
});

test('add class', function () {
    $this->element->addClass('active');
    expect($this->element->get('class'))->toEqual('active');

    $this->element->addClass('disabled');
    expect($this->element->get('class'))->toEqual('active disabled');
});

test('has class', function () {
    $this->element->set('class', 'active disabled even');
    expect($this->element->hasClass('active'))->toBeTrue();
    expect($this->element->hasClass('disabled'))->toBeTrue();
    expect($this->element->hasClass('even'))->toBeTrue();
    expect($this->element->hasClass('xdisabled'))->toBeFalse();
});

test('remove class', function () {
    $this->element->set('class', 'active disabled even');
    $this->element->removeClass('disabled');
    expect($this->element->hasClass('disabled'))->toBeFalse();
});

test('toggle class', function () {
    expect($this->element->hasClass('active'))->toBeFalse();
    $this->element->toggleClass('active');
    expect($this->element->hasClass('active'))->toBeTrue();
    $this->element->toggleClass('active');
    expect($this->element->hasClass('active'))->toBeFalse();
});

test('previous content is overwritten', function () {
    $this->element->setContent('cool');
    expect($this->element->getInnerHtml())->toEqual('cool');
});

test('content array', function () {
    $this->element->setContent(array(
        'a',
        'b',
        'c'
    ));
    expect($this->element->render())->toEqual('<div>a' . PHP_EOL . 'b' . PHP_EOL . 'c</div>');
});

test('render', function () {
    $this->element->addClass('container');
    expect((string) $this->element)->toEqual('<div class="container">Lorem ipsum...</div>');
    expect($this->element->render())->toEqual('<div class="container">Lorem ipsum...</div>');
});

test('wrap', function () {
    $this->element->addClass('container');
    $this->element->after('<i class="icon-date"></i>');
    $this->element->wrap('<div class="wrapper">', '</div>');
    expect(strpos($this->element->render(),
            '<div class="wrapper"><div class="container">Lorem ipsum...</div>') !== false)->toBeTrue();
    expect(strpos($this->element->render(), '</div><i class="icon-date"></i>') !== false)->toBeTrue();
});

test('append', function () {
    $this->element->clearContent();
    $this->element->append('<article>Article</article>');
    $this->element->append('<aside>Aside</aside>');
    expect(strpos($this->element->getInnerHtml(),
            '<article') < strpos($this->element->getInnerHtml(), '<aside'))->toBeTrue();
});

test('prepend', function () {
    $this->element->clearContent();
    $this->element->prepend('<article>Article</article>');
    $this->element->prepend('<aside>Aside</aside>');
    expect(strpos($this->element->getInnerHtml(),
            '<article') > strpos($this->element->getInnerHtml(), '<aside'))->toBeTrue();
});

test('special attributes characters', function () {
    $this->element->set('class', '<weird>"characters"');
    expect((string) $this->element)->toEqual('<div class="&lt;weird&gt;&quot;characters&quot;">Lorem ipsum...</div>');
});

test('empty attribute', function () {
    $this->element->set('class', '');
    expect((string) $this->element)->toEqual('<div class="">Lorem ipsum...</div>');
});

test('non string attribute', function () {
    $this->element->set('rows', 5);
    expect((string) $this->element)->toEqual('<div rows="5">Lorem ipsum...</div>');
});

test('self closing tag', function () {
    expect(new Hr(array(
        'class' => 'separator'
    )))->toEqual('<hr class="separator" />');
});

test('factory', function () {
    $hr = Tag::create('hr/');
    expect($hr->render())->toEqual('<hr />');

    $h1 = Tag::create('h1', array(), 'Title content');
    expect($h1->render())->toEqual('<h1>Title content</h1>');
});
