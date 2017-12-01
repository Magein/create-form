<?php

namespace Magein\createForm\library;

use Magein\createForm\library\config\CheckboxConfig;
use Magein\createForm\library\config\FileConfig;
use Magein\createForm\library\config\RadioConfig;
use Magein\createForm\library\config\SelectConfig;
use Magein\createForm\library\config\TextareaConfig;
use Magein\createForm\library\config\TextConfig;
use Magein\createForm\library\filter\Filter;
use Magein\createForm\library\filter\FormConfigFilter;

class FormFactory
{
    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var string
     */
    private $error;

    /**
     * @var Filter
     */
    private $filterClass;

    /**
     * @var array
     */
    private $formConfigClass;

    public function __construct()
    {

    }

    private function initFormConfig()
    {
        $this->formConfigClass['textConfig'] = TextConfig::class;
        $this->formConfigClass['radioConfig'] = RadioConfig::class;
        $this->formConfigClass['checkboxConfig'] = CheckboxConfig::class;
        $this->formConfigClass['selectConfig'] = SelectConfig::class;
        $this->formConfigClass['fileConfig'] = FileConfig::class;
        $this->formConfigClass['textareaConfig'] = TextareaConfig::class;
    }

    /**
     * @param string $message
     * @throws \Exception
     */
    private function throwException($message)
    {
        if ($this->debug) {
            throw new \Exception($message);
        }
    }

    /**
     * @param $error
     * @throws \Exception
     */
    private function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Filter $filterClass
     */
    public function setFilterClass(Filter $filterClass)
    {
        $this->setFilterClass($filterClass);
    }

    /**
     * @return Filter
     */
    public function getFilterClass()
    {
        return $this->filterClass;
    }

    /**
     * @param $formConfigClass
     * @return bool
     */
    public function registerFormConfig($formConfigClass)
    {
        /**
         * @var $instance FormConfig
         */
        if ($formConfigClass) {
            try {
                $instance = new $formConfigClass();
                $this->formConfigClass[$instance->getClass()] = $formConfigClass;
            } catch (\Exception $exception) {

            }
        }

        return true;
    }

    /**
     * @param $config
     * @return array
     */
    public function makeFormConfig($config)
    {
        $this->error = '';

        $config = json_decode($config, true);

        if (empty($config) || !is_array($config)) {
            $this->throwException('表单配置项json对象格式错误');
            return [];
        }

        $filterClass = null;
        if ($this->filterClass) {
            $filterClass = new $this->filterClass();
        }

        $this->initFormConfig();

        $this->filterClass = FormConfigFilter::class;

        $formConfig = [];

        foreach ($config as $item) {

            if (!is_array($item)) {
                $this->throwException('表单配置项类型错误!');
                return [];
            }

            $title = isset($item['title']) ? $item['title'] : null;
            $class = isset($item['class']) ? $item['class'] : null;

            if (!$class || !isset($this->formConfigClass[$class])) {
                $this->throwException('表单配置项类型错误,请检查属性是否正确,配置项标题：' . $title);
                return [];
            }

            /**
             * @var FormConfig $instance
             */
            $class = $this->formConfigClass[$class];

            $instance = new $class();

            if (!$instance->init($item, $filterClass)) {
                $this->throwException('表单配置项初始化失败,请检查属性是否缺少,配置项标题：' . $title);
                return [];
            };

            $formConfig[$instance->getName()] = $instance;
        }

        if (count($formConfig) !== count($config)) {
            $this->setError('表单创建失败');
            return [];
        }

        return $formConfig;
    }

    /**
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
             * @var $item FormConfig
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
     * @param array $formData
     * @param array $formConfig
     * @return array
     */
    public function checkFormData(array $formData, array $formConfig)
    {
        if (empty($formData) || empty($formConfig)) {
            return [];
        }

        foreach ($formConfig as $key => $config) {
            /**
             * @var FormConfig $config
             */
            if (is_object($config)) {
                try {
                    $value = isset($formData[$config->getName()]) ? $formData[$config->getName()] : null;
                    $result = $config->setValue($value);
                    if (false === $result) {
                        $error = $config->getPlaceholder() ?: $config->getTitle() . '不能为空';
                        $this->setError($error);
                        return [];
                    }
                    $formConfig[$key] = $config;
                } catch (\Exception $exception) {
                    return [];
                }
            }
        }

        return $formConfig;
    }

    /**
     * @param array $formData
     * @return array
     */
    public function getFormData(array $formData)
    {
        if (empty($formData)) {
            return [];
        }

        $data = [];
        /**
         * @var FormConfig $item ;
         */
        foreach ($formData as $item) {
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