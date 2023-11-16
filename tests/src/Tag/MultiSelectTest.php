<?php

use \Sirius\Html\Tag\MultiSelect;

beforeEach(function () {
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
});

test('render', function () {
    $result = (string) $this->input;
    expect(strpos($result, '<select name="answer[]"') !== false)->toBeTrue();
    expect(strpos($result, '<option value="yes" selected="selected">Yes</option>') !== false)->toBeTrue();
    expect(strpos($result, '<option value="no" selected="selected">No</option>') !== false)->toBeTrue();
    expect(strpos($result, '<option value="maybe" >Maybe</option>') !== false)->toBeTrue();
});
