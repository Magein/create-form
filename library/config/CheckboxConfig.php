<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\constant\FormErrorConstant;
use Magein\createForm\library\filter\Filter;
use Magein\createForm\library\filter\FormConfigFilter;
use Magein\createForm\library\FormConfig;

class CheckboxConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_CHECKBOX;

    /**
     * CheckboxConfig constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $data
     * @param Filter|null $filter
     * @return mixed
     */
    public function init(array $data, Filter $filter = null)
    {
        parent::init($data, $filter);

        $formConfigFilter = new FormConfigFilter();

        return $formConfigFilter->options($this->options);

    }

    /**
     * @param string $value
     * @param bool $checkLength
     * @return bool
     */
    public function setValue($value, $checkLength)
    {
        parent::setValue($value, $checkLength);

        if ($this->value) {

            /**
             * 值如果包含逗号等特殊符号，使用字符串分隔的方法会不准确，所以使用数组比较准确
             */
            if (!is_array($this->value)) {
                $this->setError(FormErrorConstant::FORM_DATA_IS_ARRAY);
                return false;
            }

            $names = [];
            foreach ($this->options as $option) {
                $names[] = $option['name'];
            }

            foreach ($this->value as $value) {
                if (!in_array($value, $names)) {
                    $this->setError(FormErrorConstant::FORM_DATA_NOT_MATCH);
                    return false;
                }
            }
        }

        return true;
    }

}