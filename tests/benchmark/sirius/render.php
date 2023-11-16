<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

class CustomForm extends \Sirius\Html\Tag
{

    protected string $tag = 'form';

    function render()
    {
        $data = $this->get('_data');
        $this->set('action', $data['form_url']);
        foreach ($data['fields'] as $inputName => $field) {
            $this->append($this->builder->make('custom-form-group', [
                '_name'     => $inputName,
                '_classes' => $data['classes'],
                '_field'   => $field
            ]));
        }
        $this->append($this->builder->make('submit', array('class' => $data['classes']['submit'])));

        return parent::render();
    }
}

class CustomFormGroup extends \Sirius\Html\Tag
{
    protected string $tag = 'div';

    function render()
    {
        $classes = $this->get('_classes');
        $name = $this->get('_name');
        $field   = $this->get('_field');
        $this->set('class', $classes['form_group']);
        $this->setContent([
            $this->builder->make('label', ['class' => $classes['form_label']], $field['label']),
            $this->builder->make('text', ['class' => $classes['form_control'], 'name' => $name], $field['value'])
        ]);

        return parent::render();
    }
}

$h = new \Sirius\Html\Builder();
$h->registerTag('custom-form', 'CustomForm');
$h->registerTag('custom-form-group', 'CustomFormGroup');

$data = array(
    'form_url' => 'http://www.google.com',
    'classes'  => array(
        'form_group'   => 'form-group',
        'form_label'   => 'form-label',
        'form_control' => 'form-control',
        'submit'       => 'btn btn-primary'
    ),
    'fields'   => [
        'email'            => array(
            'label' => 'Your email',
            'value' => 'me@domain.com',
        ),
        'username'         => array(
            'label' => 'Username',
            'value' => 'twig',
        ),
        'password'         => array(
            'label' => 'Username',
            'value' => 'password',
        ),
        'confirm_password' => array(
            'label' => 'Confirm password',
            'value' => 'password',
        )
    ]
);

echo $h->make('custom-form', array('_data' => $data));

$start = microtime(true);
for ($i = 0; $i < 100000; $i++) {
    $result = $h->make('custom-form', array('_data' => $data))->render();
}

echo PHP_EOL, '-----------------------------', PHP_EOL;
echo 'SIRIUS HTML', PHP_EOL;
echo '-----------------------------', PHP_EOL;
echo 'Duration: ', microtime(true) - $start, PHP_EOL;
echo 'Memory:', round(memory_get_peak_usage(true) / 1024 / 1024, 2), 'MB';
echo PHP_EOL, '-----------------------------', PHP_EOL;
