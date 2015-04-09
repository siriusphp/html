<?php
namespace Sirius\Html;

class BuilderTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->builder = new Builder();
        
        $this->builder->registerTag('some-tag', function ($content = null, $props = null, $builder)
        {
            return Tag::create('other-tag', $content, $props, $builder);
        });
    }

    function testCustomTag()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->make('some-tag', 'Content')
            ->render());
    }

    function testMagicCall()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->someTag('Content')
            ->render());
    }

    function testRegisteredTag()
    {
        $this->assertEquals('<img src="some/file.jpg">', $this->builder->img(null, array(
            'src' => 'some/file.jpg'
        )));
    }

    function testNonRegisteredTag()
    {
        $this->assertEquals('<hr class="separator">', $this->builder->make('hr/', null, array(
            'class' => 'separator'
        )));
    }
    
    function testExceptionThrownForInvalidFactories()
    { 
        $this->setExpectedException('InvalidArgumentException');
        $this->builder->registerTag('exception', function() {
        	return '';
        });
        $this->builder->make('exception');
    }   

    function testComplexScenario()
    {
        $html = $this->builder->make('form', [
            [
                'div',
                [
                    [
                        'label',
                        'Username'
                    ],
                    [
                        'input',
                        'my_username',
                        [
                            'type' => 'text'
                        ],
                    ],
                    [
                        'div',
                        'Enter your desired username',
                        [
                            'class' => 'form-help'
                        ]
                    ]
                ],
                [
                    'class' => 'form-control'
                ]
            ]
            
        ], [
            'id' => 'user-add',
            'class' => 'form-horizontal'
        ]);
        $renderedHtml = $html->render();
        $this->assertTrue(strpos($renderedHtml, '<div class="form-help">') !== false);
        $this->assertTrue(strpos($renderedHtml, '<label>Username') !== false);
        $this->assertTrue(strpos($renderedHtml, '<input type="text">') !== false);
    }
}