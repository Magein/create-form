<?php

spl_autoload_register(function ($class) {
    preg_match('/Magein\\\createform\\\([a-zA-Z\\\]+)/', $class, $matches);
    $dir = isset($matches[1]) ? $matches[1] : null;
    if ($dir) {
        $dir = '../' . $dir . '.php';
        if (is_file($dir)) {
            include $dir;
        }
    }
});

use Magein\createForm\library\FormFactory;

// 一个json类型表单配置项
$formConfig = json_encode(getOption(), JSON_UNESCAPED_UNICODE);

//echo $formConfig;
//die();


$formFactory = new FormFactory();
$formFactory->debug = true;

// 验证表单配置项,验证通过返回一个对象
$formConfig = $formFactory->makeFormConfig($formConfig);
if (null === $formConfig) {
    echo $formFactory->getError();
    exit();
}

//var_dump($formConfig);
//die();

// 接收formData值，验证通过返回一个对象，对象根据type值生成
$formData = $formFactory->checkFormData(formData(), $formConfig);
if (null === $formData) {
    echo $formFactory->getError();
    exit();
}

//var_dump($formData);
//die();


// 获取formData的值，得到表单项的 [name=>value] 的数组
$result = $formFactory->getFormData($formData);
var_dump($result);

/**
 * 接收的formData数据
 * @return array
 */
function formData()
{
    return [
        'username' => 'null',
        'n1_1' => 0,
        'n1_2' => [0, 2],
        'n1_3' => 0,
        'n1_4' => '2005-10-25',
        'n1_5' => '<script>alert("scr恩啊哦")</script>',
        'n1_6' => 'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=3254987598,596718202&fm=27&gp=0.jpg'
    ];
}

/**
 * 设置表单项
 * 前段传递一个json类型的字符串，后端处理会转化成数组的方式
 * @return array
 */
function getOption()
{
    $username = new \Magein\createForm\UserConfig();
    return [
        $username->toArray(),
        [
            'title' => '性别',
            'type' => 'radio',
            'name' => 'n1_1',
            'value' => 0,
            'placeholder' => '请选择性别',
            'required' => 1,
            'length' => [1, 1],
            'options' => ['男', '女']
        ],
        [
            'title' => '特长',
            'type' => 'checkbox',
            'name' => 'n1_2',
            'value' => [0, 1, 2],
            'placeholder' => '请选择特长',
            'required' => 1,
            'length' => [1, 1],
            'options' => ['跑步', 'LOL', '吃']

        ],
        [
            'title' => '年级',
            'type' => 'select',
            'name' => 'n1_3',
            'value' => 3,
            'placeholder' => '请选择年级',
            'required' => 1,
            'length' => [1, 1],
            'options' => ['高一', '高二', '高三']
        ],
        [
            'title' => '入学时间',
            'type' => 'date',
            'name' => 'n1_4',
            'value' => '',
            'length' => [1, 1],
            'placeholder' => '请输入入学时间',
            'required' => 1,
            'options' => []
        ],
        [
            'title' => '简介',
            'type' => 'textarea',
            'name' => 'n1_5',
            'value' => '请输入简介',
            'placeholder' => '请输入简介',
            'required' => 1,
            'length' => [1, 40],
            'options' => []
        ],
        [
            'title' => '照片',
            'type' => 'image',
            'name' => 'n1_6',
            'value' => 'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=3254987598,596718202&fm=27&gp=0.jpg',
            'placeholder' => '请上传照片',
            'required' => 1,
            'length' => [1, 1],
            'options' => []
        ]

    ];
}

