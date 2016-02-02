<?php
namespace Sirius\Html;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Builder
     */
    protected $builder;

    function setUp()
    {
        $this->builder = new Builder();

        $this->builder->registerTag('some-tag', function ($content = null, $props = null, $builder) {
            return Tag::create('other-tag', $content, $props, $builder);
        });
    }

    function testCustomTag()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->make('some-tag', array(), 'Content')
                                                                            ->render());
    }

    function testMagicCall()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->someTag(array(), 'Content')
                                                                            ->render());
    }

    function testRegisteredTag()
    {
        $this->assertEquals('<img src="some/file.jpg" />', $this->builder->img(array(
            'src' => 'some/file.jpg'
        )));
    }

    function testNonRegisteredTag()
    {
        $this->assertEquals('<hr class="separator" />', $this->builder->make('hr/', array(
            'class' => 'separator'
        )));
    }

    function testExceptionThrownForInvalidFactories()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->builder->registerTag('exception', function () {
            return '';
        });
        $this->builder->make('exception');
    }

    function testComplexScenario()
    {
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
        $this->assertTrue(strpos($renderedHtml, '<div class="form-help">') !== false);
        $this->assertTrue(strpos($renderedHtml, '<label>Username') !== false);
        $this->assertTrue(strpos($renderedHtml, '<input type="text">') !== false);
    }

    function testOptions()
    {
        $this->assertNull($this->builder->getOption('use_bootstrap'));
        $this->builder->setOption('use_bootstrap', true);
        $this->assertTrue($this->builder->getOption('use_bootstrap'));
    }

    function testWith()
    {
        $this->assertNull($this->builder->getOption('use_bootstrap'));
        $newBuilder = $this->builder->with(array( 'use_bootstrap' => true ));

        $this->assertNotEquals($this->builder, $newBuilder);
        $this->assertTrue($newBuilder->getOption('use_bootstrap'));
    }
}