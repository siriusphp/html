<?php

use \Sirius\Html\Tag\Text;

beforeEach(function () {
    $this->input = new Text(array(
        'name'     => 'username',
        'disabled' => true,
        'class'    => 'not-valid'
    ), 'siriusforms');
});

test('attributes', function () {
    expect($this->input->getValue())->toEqual('siriusforms');
    expect($this->input->get('name'))->toEqual('username');
    expect($this->input->hasClass('not-valid'))->toBeTrue();
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input class="not-valid" disabled name="username" value="siriusforms" />');
});
