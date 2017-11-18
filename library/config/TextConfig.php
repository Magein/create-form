<?php

namespace Magein\createform\library\config;

use Magein\createform\library\constant\FormConfigTypeConstant;
use Magein\createform\library\FormConfig;
use Magein\createform\library\FormError;

class TextConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_TEXT;

    /**
     * TextConfig constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setValue($value)
    {
        parent::setValue($value);

        if (!is_string($this->value) && !is_int($this->value)) {
            return false;
        }

        return true;
    }
}