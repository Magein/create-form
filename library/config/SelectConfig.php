<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\FormConfig;

class SelectConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_SELECT;

    /**
     * SelectConfig constructor.
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

            if (!is_string($this->value) && !is_int($this->value)) {
                return false;
            }

            if (in_array($value, $this->options)) {
                return false;
            }
        }

        return true;
    }
}