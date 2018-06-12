<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\constant\FormErrorConstant;
use Magein\createForm\library\filter\Filter;
use Magein\createForm\library\filter\FormConfigFilter;
use Magein\createForm\library\FormConfig;

class RadioConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_RADIO;

    /**
     * RadioConfig constructor.
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

        /**
         * @var FormConfigFilter $filter
         */
        $result = $filter->options($this->options);

        return $result;
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

            if (!is_string($this->value) && !is_int($this->value)) {
                $this->setError(FormErrorConstant::FORM_DATA_IS_STRING, $this->title);
                return false;
            }

            $names = [];
            foreach ($this->options as $option) {
                $names[] = $option['name'];
            }

            if (!in_array($this->value, $names)) {
                $this->setError(FormErrorConstant::FORM_DATA_NOT_MATCH, $this->title);
                return false;
            }
        }

        return true;
    }
}