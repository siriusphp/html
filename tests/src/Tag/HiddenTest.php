<?php

use \Sirius\Html\Tag\Hidden;

beforeEach(function () {
    $this->input = new Hidden(array(
        'name' => 'token'
    ), '123');
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input name="token" type="hidden" value="123" />');
});
