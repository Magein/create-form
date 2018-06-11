<?php

spl_autoload_register(function ($class) {
    preg_match('/Magein\\\createForm\\\([a-zA-Z\\\]+)/', $class, $matches);
    $dir = isset($matches[1]) ? $matches[1] : null;
    if ($dir) {
        $dir = './' . $dir . '.php';
        if (is_file($dir)) {
            include $dir;
        }
    }
});


use Magein\createForm\library\FormFactory;

$formConfig = include('./form_config_format.json');

$formFactory = new FormFactory();
$formConfig = $formFactory->makeFormConfig($formConfig);



