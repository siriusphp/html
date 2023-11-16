<?php

use \Sirius\Html\Tag\Radio;

beforeEach(function () {
    $this->input = new Radio(array(
        'name'  => 'gender',
        'value' => 'male'
    ), 'male');
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input checked="checked" name="gender" type="radio" value="male" />');
});
