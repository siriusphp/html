<?php

use \Sirius\Html\Tag\File;

beforeEach(function () {
    $this->input = new File(array(
        'name'  => 'picture',
        'class' => 'upload'
    ));
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input class="upload" name="picture" type="file" />');
});
