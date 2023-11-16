<?php

use \Sirius\Html\Tag;
use \Sirius\Html\Builder;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->builder = new Builder();

    $this->builder->registerTag('some-tag', function ($content = null, $props = null, $builder = null) {
        return Tag::create('other-tag', $content, $props, $builder);
    });
});

test('custom tag', function () {
    expect($this->builder->make('some-tag', array(), 'Content')
                                                                        ->render())->toEqual('<other-tag>Content</other-tag>');
});

test('magic call', function () {
    expect($this->builder->someTag(array(), 'Content')
                                                                        ->render())->toEqual('<other-tag>Content</other-tag>');
});

test('registered tag', function () {
    expect((string) $this->builder->img(array(
        'src' => 'some/file.jpg'
    )))->toEqual('<img src="some/file.jpg" />');
});

test('non registered tag', function () {
    expect($this->builder->make('hr/', array(
        'class' => 'separator'
    )))->toEqual('<hr class="separator" />');
});

test('exception thrown for invalid factories', function () {
    $this->expectException('InvalidArgumentException');
    $this->builder->registerTag('exception', function () {
        return '';
    });
    $this->builder->make('exception');
});

test('complex scenario', function () {
    $html         = $this->builder->make('form', array(
        'id'    => 'user-add',
        'class' => 'form-horizontal'
    ), array(
        array(
            'div',
            array(
                'class' => 'form-control'
            ),
            array(
                array(
                    'label',
                    array(),
                    'Username'
                ),
                array(
                    'input',
                    array(
                        'type' => 'text'
                    ),
                    'my_username'
                ),
                array(
                    'div',
                    array(
                        'class' => 'form-help'
                    ),
                    'Enter your desired username'
                )
            )
        )

    ));
    $renderedHtml = $html->render();
    expect(strpos($renderedHtml, '<div class="form-help">') !== false)->toBeTrue();
    expect(strpos($renderedHtml, '<label>Username') !== false)->toBeTrue();
    expect(strpos($renderedHtml, '<input type="text">') !== false)->toBeTrue();
});

test('options', function () {
    expect($this->builder->getOption('use_bootstrap'))->toBeNull();
    $this->builder->setOption('use_bootstrap', true);
    expect($this->builder->getOption('use_bootstrap'))->toBeTrue();
});

test('with', function () {
    expect($this->builder->getOption('use_bootstrap'))->toBeNull();
    $newBuilder = $this->builder->with(array( 'use_bootstrap' => true ));

    $this->assertNotEquals($this->builder, $newBuilder);
    expect($newBuilder->getOption('use_bootstrap'))->toBeTrue();
});
