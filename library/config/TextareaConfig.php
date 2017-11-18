<?php

namespace Magein\createform\library\config;

use Magein\createform\library\constant\FormConfigTypeConstant;
use Magein\createform\library\FormConfig;

class TextareaConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_TEXTAREA;

    /**
     * TextareaConfig constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

}