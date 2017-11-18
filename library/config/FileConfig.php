<?php

namespace Magein\createform\library\config;

use Magein\createform\library\constant\FormConfigTypeConstant;
use Magein\createform\library\FormConfig;

class FileConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_FILE;

    /**
     * FileConfig constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

}