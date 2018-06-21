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

//var_dump($formConfig);
//die();

/**
 *  效验通过可以直接保存传递的 json字符串，如果保存为对象则需要序列化
 */
$saveObject = serialize($formConfig);
/**
 * 保存 $saveObject 即可
 */


/**
 * 模拟表单提交的数据
 */
$formData = [
    'e901n719' => '小马哥',
    'e901n458' => '1',
    'e901n542' => '18',
    'e741n632' => 'http://www.baidu.com',
    'e741n412' => '这里是简介',
    'e741n596' => ['吃', '喝']
];

/**
 * 效验提交的数据
 *
 * 注意：checkFormData() 第二个配置项。类型为一个数组，数组里面是对象，所以需要保存的是json 需要调用 makeFormConfig() 转化成对象在传递进去
 */
$result = $formFactory->checkFormData($formData, $formConfig);

if (empty($result)) {
    echo $formFactory->getError();
    echo '<br/>';
    echo $formFactory->getErrorConfig();
    exit();
}

var_dump($result);



