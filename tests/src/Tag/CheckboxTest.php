<?php

use \Sirius\Html\Tag\Checkbox;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->input = new Checkbox(array(
        'name'  => 'agree_to_terms',
        'value' => 'yes'
    ), 'yes');
});

test('render', function () {
    expect((string) $this->input)->toEqual('<input checked="checked" name="agree_to_terms" type="checkbox" value="yes" />');

    // change value
    $this->input->setValue('no');
    expect((string) $this->input)->toEqual('<input name="agree_to_terms" type="checkbox" value="yes" />');
});

test('multiple values', function () {
    $this->input->setValue(array(
        'yes',
        'maybe'
    ));
    expect((string) $this->input)->toEqual('<input checked="checked" name="agree_to_terms" type="checkbox" value="yes" />');

    $this->input->setValue(array(
        'no',
        'maybe'
    ));
    expect((string) $this->input)->toEqual('<input name="agree_to_terms" type="checkbox" value="yes" />');
});
