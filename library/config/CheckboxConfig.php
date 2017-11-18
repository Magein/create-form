<?php

namespace Magein\createform\library\config;

use Magein\createform\library\constant\FormConfigTypeConstant;
use Magein\createform\library\FormConfig;

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
     * @return bool
     */
    public function init(array $data)
    {
        parent::init($data);

        return $this->checkOptions($this->options);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setValue($value)
    {
        parent::setValue($value);

        if ($this->value) {

            if (!is_array($this->value)) {
                return false;
            }

            foreach ($this->value as $value) {
                if (!in_array($value, $this->options)) {
                    return false;
                }
            }
        }

        return true;
    }

}