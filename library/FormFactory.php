<?php

namespace Magein\createForm\library;

use Magein\createForm\library\config\CheckboxConfig;
use Magein\createForm\library\config\FileConfig;
use Magein\createForm\library\config\RadioConfig;
use Magein\createForm\library\config\SelectConfig;
use Magein\createForm\library\config\TextareaConfig;
use Magein\createForm\library\config\TextConfig;
use Magein\createForm\library\constant\FormConfigClassConstant;
use Magein\createForm\library\constant\FormErrorConstant;

class FormFactory
{

    use FormError;

    /**
     * @var array
     */
    private $formConfigClass;

    /**
     * FormFactory constructor.
     */
    public function __construct()
    {
        $this->initFormConfig();
    }

    /**
     * 初始化表单配置
     */
    private function initFormConfig()
    {
        $this->formConfigClass[FormConfigClassConstant::TYPE_TEXT_CLASS] = TextConfig::class;
        $this->formConfigClass[FormConfigClassConstant::TYPE_RADIO_CLASS] = RadioConfig::class;
        $this->formConfigClass[FormConfigClassConstant::TYPE_CHECKBOX_CLASS] = CheckboxConfig::class;
        $this->formConfigClass[FormConfigClassConstant::TYPE_SELECT_CLASS] = SelectConfig::class;
        $this->formConfigClass[FormConfigClassConstant::TYPE_FILE_CLASS] = FileConfig::class;
        $this->formConfigClass[FormConfigClassConstant::TYPE_TEXTAREA_CLASS] = TextareaConfig::class;
    }

    /**
     * 注册表单配置项处理类
     * @param string $formConfigClass static::class的结果
     * @param string|null $key 指定的键,不传递则自动获取，这里与 FormConfig 中的 class 属性对应（即前段传递的json数据中必须指定的表单项处理类名称）
     * @return bool
     */
    public function registerFormConfig($formConfigClass, $key = null)
    {
        /**
         * @var $instance FormConfig
         */
        if ($formConfigClass && $formConfigClass instanceof FormConfig) {

            $instance = new $formConfigClass();

            if (null === $key) {
                $key = $instance->getClass();
            }

            $this->formConfigClass[$key] = $formConfigClass;

            return true;
        }

        $this->setError(FormErrorConstant::FORM_CONFIG_CLASS_ERROR);

        return false;
    }

    /**
     * 检测表单项的每一项配置是否正确，最后返回一个数组，数组中的每一项都是一个FormConfig对象
     * @param $config
     * @return array
     */
    public function makeFormConfig($config)
    {
        $config = json_decode($config, true);

        if (empty($config) || !is_array($config)) {
            $this->setError(FormErrorConstant::FORM_CONFIG_DECODE_FAIL);
            return [];
        }

        $formConfig = [];

        foreach ($config as $key => $item) {

            $errorKey = '第' . ($key + 1) . '项';

            if (!is_array($item) || empty($item)) {
                $this->setError(FormErrorConstant::FORM_CONFIG_FORMAT_ERROR, $errorKey);
                return [];
            }

            if (!isset($item['class'])) {
                $this->setError(FormErrorConstant::FORM_CONFIG_CLASS_NOT_FOUND, $errorKey);
                return [];
            }

            if (!isset($this->formConfigClass[$item['class']])) {
                $this->setError(FormErrorConstant::FORM_CONFIG_CLASS_ERROR, $item['class']);
                return [];
            }

            /**
             * @var FormConfig $instance
             */
            $class = $this->formConfigClass[$item['class']];
            $instance = new $class();

            if (!$instance->init($item)) {
                $this->setError($instance->getCode(), $instance->getErrorConfig(), $instance->getError());
                return [];
            };

            if (isset($formConfig[$instance->getName()])) {
                $this->setError(FormErrorConstant::FORM_CONFIG_NAME_NOT_UNIQUE, $instance->getName());
                return [];
            }

            $formConfig[$instance->getName()] = $instance;
        }

        return $formConfig;
    }

    /**
     * 把表单项转化为数组（调用 makeFormConfig() 方法后的数据），数组中的每一表单项都是一个对象
     * @param array $formConfig
     * @return array
     */
    public function transFormConfigToArray(array $formConfig)
    {
        if (empty($formConfig) || !is_array($formConfig)) {
            return [];
        }

        $result = [];

        if ($formConfig && is_array($formConfig)) {
            /**
             * @var $config FormConfig
             */
            foreach ($formConfig as $key => $config) {
                try {
                    $result[] = $config->toArray();
                } catch (\Exception $exception) {
                    return [];
                }
            }
        }

        return $result;
    }

    /**
     * 把表单项转化为json（调用 makeFormConfig() 方法后的数据），数组中的每一表单项都是一个对象
     * @param array $formConfig
     * @return null|string
     */
    public function transFormConfigToJson(array $formConfig)
    {
        $formConfig = $this->transFormConfigToArray($formConfig);

        if (!$formConfig) {
            return null;
        }

        return json_encode(array_values($formConfig), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 检测提交的表单数据是否正确
     * @param array $formData ["name":"value","name":"value"]形式的数组，name对应的是表单配置中的每一项的name值
     * @param array $formConfig 数组的每一项都是一个对象（调用 makeFormConfig() 方法后返回的数据）
     * @return array
     */
    public function checkFormData(array $formData, array $formConfig)
    {
        foreach ($formConfig as $key => $config) {
            /**
             * @var FormConfig $config
             */
            if (is_object($config) && isset($this->formConfigClass[$config->getClass()])) {
                try {
                    $name = $config->getName();
                    $value = isset($formData[$name]) ? $formData[$name] : null;
                    $result = $config->setValue($value);
                    if (false === $result) {
                        $this->setError($config->getCode(), $config->getErrorConfig());
                        return [];
                    }
                    $formConfig[$key] = $config;
                } catch (\Exception $exception) {
                    $this->setError(FormErrorConstant::FORM_CONFIG_CLASS_NOT_FOUND);
                    return [];
                }
            } else {
                $this->setError(FormErrorConstant::FORM_CONFIG_CLASS_NOT_FOUND);
                return [];
            }
        }

        return $formConfig;
    }

    /**
     * 获取表单配置项中的值，这里是表单项是调用 checkFormData() 方法后返回的数组(每一项都调用了setValue)
     * @param array $formConfig
     * @return array
     */
    public function getFormData(array $formConfig)
    {
        if (empty($formConfig)) {
            return [];
        }

        $data = [];
        /**
         * @var FormConfig $item ;
         */
        foreach ($formConfig as $item) {
            if (is_object($item)) {
                try {
                    $data[$item->getName()] = $item->getValue();
                } catch (\Exception $exception) {
                    return [];
                }
            }

        }

        return $data;
    }

    /**
     * 获取表单项的标题（调用了 makeFormConfig() 后返回的数组）
     * @param array $formConfig
     * @return array
     */
    public function getFormConfig(array $formConfig)
    {
        $data = [];

        if (empty($formConfig)) {
            return $data;
        }
        /**
         * @var FormConfig $item ;
         */
        foreach ($formConfig as $item) {
            if (is_object($item)) {
                try {
                    $data[$item->getName()] = $item->getTitle();
                } catch (\Exception $exception) {
                    return [];
                }
            }
        }

        return $data;
    }
}