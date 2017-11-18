<?php

namespace Magein\createForm\library;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\config\CheckboxConfig;
use Magein\createForm\library\config\FileConfig;
use Magein\createForm\library\config\RadioConfig;
use Magein\createForm\library\config\SelectConfig;
use Magein\createForm\library\config\TextareaConfig;
use Magein\createForm\library\config\TextConfig;

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
     * @param $config
     * @return array|null
     */
    public function makeFormConfig($config)
    {
        $config = json_decode($config, true);

        if (empty($config) || !is_array($config)) {
            return [];
        }

        $formConfig = [];

        foreach ($config as $item) {
            $type = isset($item['type']) ? $item['type'] : null;
            switch ($type) {
                case FormConfigTypeConstant::TYPE_TEXT:
                    $instance = new TextConfig();
                    break;
                case FormConfigTypeConstant::TYPE_RADIO:
                    $instance = new RadioConfig();
                    break;
                case FormConfigTypeConstant::TYPE_CHECKBOX:
                    $instance = new CheckboxConfig();
                    break;
                case FormConfigTypeConstant::TYPE_SELECT:
                    $instance = new SelectConfig();
                    break;
                case FormConfigTypeConstant::TYPE_TEXTAREA:
                    $instance = new TextareaConfig();
                    break;
                case FormConfigTypeConstant::TYPE_FILE:
                    $instance = new FileConfig();
                    break;
                default:
                    $instance = [];
            }

            $title = isset($item['title']) ? $item['title'] : null;

            if (!$instance) {
                $this->throwException('表单配置项类型错误,请检查type属性是否正确,配置项标题：' . $title);
                return null;
            }

            if (!$instance->init($item)) {
                $this->throwException('表单配置项初始化失败,请检查属性是否缺少,配置项标题：' . $title);
                return null;
            };

            $formConfig[$instance->getName()] = $instance;
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

        if ($formConfig && is_array($formConfig)) {
            /**
             * @var $item FormConfig
             */
            foreach ($formConfig as $key => $config) {
                try {
                    $formConfig[$key] = $config->toArray();
                } catch (\Exception $exception) {
                    return [];
                }
            }
        }

        return $formConfig;
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

        return json_encode($formConfig, JSON_UNESCAPED_UNICODE);
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
                        $this->setError($config->getPlaceholder());
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