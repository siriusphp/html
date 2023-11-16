<?php

use \Sirius\Html\Tag\Textarea;

beforeEach(function () {
    $this->input = new Textarea(array(
        'name' => 'comment',
        'cols' => '30'
    ), 'Sirius HTML rocks!');
});

test('render', function () {
    expect((string) $this->input)->toEqual('<textarea cols="30" name="comment">Sirius HTML rocks!</textarea>');
});
