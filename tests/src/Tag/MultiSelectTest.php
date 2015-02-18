<?php
namespace Sirius\Html\Tag;

class MultiSelectTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new MultiSelect(array(
            'name' => 'answer'
        ), array(
            'yes',
            'no'
        ), array(
            'first_option' => '--select--',
            'options' => array(
                'yes' => 'Yes',
                'no' => 'No',
                'maybe' => 'Maybe'
            )
        ));
    }

    function testRender()
    {
        $result = (string) $this->input;
        $this->assertTrue(strpos($result, '<select name="answer[]"') !== false);
        $this->assertTrue(strpos($result, '<option value="yes" selected="selected">Yes</option>') !== false);
        $this->assertTrue(strpos($result, '<option value="no" selected="selected">No</option>') !== false);
        $this->assertTrue(strpos($result, '<option value="maybe" >Maybe</option>') !== false);
    }
}
