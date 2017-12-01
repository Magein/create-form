<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\filter\Filter;
use Magein\createForm\library\filter\FormConfigFilter;
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
     * @return bool
     */
    public function setValue($value)
    {
        parent::setValue($value);

        if ($this->value) {

            if (!is_string($this->value) && !is_int($this->value)) {
                return false;
            }

            $names = [];
            foreach ($this->options as $option) {
                $names[] = $option['name'];
            }

            if (!in_array($this->value, $names)) {
                return false;
            }
        }

        return true;
    }
}