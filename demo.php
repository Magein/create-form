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

$formConfig = file_get_contents('./form_config_format.json');

$formFactory = new FormFactory();
$formConfig = $formFactory->makeFormConfig($formConfig);

if (empty($formConfig)) {
    echo $formFactory->getError();
    echo '<br/>';
    echo $formFactory->getErrorConfig();
    exit();
}

$formData = [
    'e901n719' => '小马哥',
    'e901n458' => '1',
    'e901n542' => '18',
    'e741n632' => 'http://www.baidu.com',
    'e741n412' => '这里是简介',
    'e741n586' => ['吃', '喝']
];

$checkLength = false;

$result = $formFactory->checkFormData($formData, $formConfig, $checkLength);

if (empty($result)) {
    echo $formFactory->getError();
    echo '<br/>';
    echo $formFactory->getErrorConfig();
    exit();
}

var_dump($result);



