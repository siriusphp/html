<?php
namespace Sirius\Html\Tag;

class FileTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new File(array(
            'name'  => 'picture',
            'class' => 'upload'
        ));
    }

    function testRender()
    {
        $this->assertEquals('<input class="upload" name="picture" type="file">', (string) $this->input);
    }
}
