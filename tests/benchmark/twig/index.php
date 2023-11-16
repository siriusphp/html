<?php

use Twig\Environment;

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, array(
    'cache' => __DIR__ . '/cache',
));

$data = array(
    'form_url' => 'http://www.google.com',
    'classes'  => array(
        'form_group'   => 'form-group',
        'form_label'   => 'form-label',
        'form_control' => 'form-control',
        'submit'       => 'btn btn-primary'
    ),
    'fields'   => [
        'email'    => array(
            'label' => 'Your email',
            'value' => 'me@domain.com',
        ),
        'username' => array(
            'label' => 'Username',
            'value' => 'twig',
        ),
        'password' => array(
            'label' => 'Username',
            'value' => 'password',
        ),
        'confirm_password' => array(
            'label' => 'Confirm password',
            'value' => 'password',
        )
    ]
);

echo $twig->render('index.html', $data);

$start = microtime(true);
for ($i = 0; $i < 100000; $i++) {
    $twig->render('index.html', $data);
}

echo PHP_EOL, '-----------------------------', PHP_EOL;
echo 'TWIG', PHP_EOL;
echo '-----------------------------', PHP_EOL;
echo 'Duration: ', microtime(true) - $start, PHP_EOL;
echo 'Memory:', round(memory_get_peak_usage(true) / 1024 / 1024, 2), 'MB';
echo PHP_EOL, '-----------------------------', PHP_EOL;
