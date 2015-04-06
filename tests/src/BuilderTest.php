<?php
namespace Sirius\Html;

class BuilderTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->builder = new Builder();
        
        $this->builder->registerTag('some-tag', function ($attr = null, $content = null, $data = null, $builder)
        {
            return Tag::create('other-tag', $attr, $content, $data, $builder);
        });
    }

    function testCustomTag()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->make('some-tag', null, 'Content')
            ->render());
    }

    function testMagicCall()
    {
        $this->assertEquals('<other-tag>Content</other-tag>', $this->builder->someTag(null, 'Content')
            ->render());
    }

    function testRegisteredTag()
    {
        $this->assertEquals('<img src="some/file.jpg">', $this->builder->img(array(
            'src' => 'some/file.jpg'
        )));
    }

    function testNonRegisteredTag()
    {
        $this->assertEquals('<hr class="separator">', $this->builder->make('hr/', array(
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
            'id' => 'user-add',
            'class' => 'form-horizontal'
        ], [
            [
                'div',
                [
                    'class' => 'form-control'
                ],
                [
                    [
                        'label',
                        [],
                        'Username'
                    ],
                    [
                        'input',
                        [
                            'type' => 'text'
                        ],
                        'my_username'
                    ],
                    [
                        'div',
                        [
                            'class' => 'form-help'
                        ],
                        'Enter your desired username'
                    ]
                ]
            ]
            
        ]);
        $renderedHtml = $html->render();
        $this->assertTrue(strpos($renderedHtml, '<div class="form-help">') !== false);
        $this->assertTrue(strpos($renderedHtml, '<label>Username') !== false);
        $this->assertTrue(strpos($renderedHtml, '<input type="text">') !== false);
    }
}