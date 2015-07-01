<?php
namespace Sirius\Html\Tag;

class MultiSelectTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new MultiSelect(array(
            'name'          => 'answer',
            '_first_option' => '--select--',
            '_options'      => array(
                'yes'   => 'Yes',
                'no'    => 'No',
                'maybe' => 'Maybe'
            )
        ), array(
            'yes',
            'no'
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
