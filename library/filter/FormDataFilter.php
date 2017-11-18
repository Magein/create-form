<?php

namespace Magein\createform\library\filter;


class FormDataFilter extends Filter
{
    /**
     * @param $value
     * @return string
     */
    public function text($value)
    {
        return $this->toString($value);
    }

    /**
     * @param $value
     * @param array $options
     * @return mixed|null
     */
    public function radio($value, array $options)
    {
        if (empty($options)) {
            return null;
        }

        if (isset($options[$value])) {
            return $options[$value];
        }

        return null;
    }

    /**
     * @param array $value
     * @param array $options
     * @return array|null
     */
    public function checkbox(array $value, array $options)
    {

        if (empty($options)) {
            return null;
        }

        if (!array_diff(array_keys($value), array_keys($options))) {
            return $value;
        }

        return null;
    }

    /**
     * @param $value
     * @param array $options
     * @return mixed|null
     */
    public function select($value, array $options)
    {
        if (empty($options)) {
            return null;
        }

        if (isset($options[$value])) {
            return $options[$value];
        }

        return null;
    }

    /**
     * @param $value
     * @return string
     */
    public function textarea($value)
    {
        return $this->toString($value);
    }

    /**
     * @param $value
     * @return array|null
     */
    public function image($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $item) {
            if (!preg_match('/^http/', $item)) {
                return null;
            }
        }

        return $value;
    }

    /**
     * @param $value
     * @return false|int|null
     */
    public function date($value)
    {
        $time = strtotime($value);

        if (false === $time || $time < 0) {
            return null;
        }

        return $time;
    }
}