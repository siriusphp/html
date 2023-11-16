<?php

use \Sirius\Html\Tag\Password;

beforeEach(function () {
    $this->input = new Password(array(
        'name'  => 'password',
        'class' => 'not-valid'
    ), '0123456');
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input class="not-valid" name="password" type="password" />');
});
