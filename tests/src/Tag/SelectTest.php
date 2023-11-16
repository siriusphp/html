<?php

use \Sirius\Html\Tag\Select;

beforeEach(function () {
    $this->input = new Select(array(
        'name'          => 'answer',
        'class'         => 'dropdown',
        '_first_option' => '--select--',
        '_options'      => array(
            'yes'   => 'Yes',
            'no'    => 'No',
            'maybe' => 'Maybe'
        )
    ), 'maybe');
});

test('render', function () {
    $result = (string) $this->input;
    expect(strpos($result, '<select class="dropdown"') !== false)->toBeTrue();
    expect(strpos($result, '<option value="maybe" selected="selected">Maybe</option>') !== false)->toBeTrue();
    expect(strpos($result, '<option value="yes" >Yes</option>') !== false)->toBeTrue();
});
