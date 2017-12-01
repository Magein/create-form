<?php

namespace Magein\createForm\library\filter;

class FormConfigFilter extends Filter
{
    /**
     * @param $title
     * @return bool
     */
    public function title($title)
    {
        $title = $this->toString($title);

        if (empty($title)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function name($name)
    {

        if (!preg_match('/[\w]+/', $name)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $required
     * @return int
     */
    public function required($required)
    {
        if (in_array($required, [0, 1])) {
            return true;
        }

        return false;
    }

    /**
     * @param array $options
     * @return string
     */
    public function options($options)
    {
        if (empty($options)) {
            return false;
        }

        if (!is_array($options)) {
            return false;
        }

        $names = [];

        foreach ($options as $key => $option) {

            if (!isset($option['name']) || !isset($option['value'])) {
                return false;
            }

            if (!isset($option['value'])) {
                return false;
            }

            $option['name'] = trim($option['name']);
            $option['value'] = trim($option['value']);

            $options[$key] = $option;

            $names[] = $option['name'];
        }

        if (count(array_unique($names)) != count($names)) {
            return false;
        }

        return true;
    }

    /**
     * @param $length
     * @return bool
     */
    public function length($length)
    {
        if (!is_array($length)) {
            $length = json_decode($length, true);
        }

        if (count($length) > 2) {
            $this->setError('长度属性的值为数组类型');
            return false;
        }

        return true;
    }
}