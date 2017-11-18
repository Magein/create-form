<?php

namespace Magein\createform\library\filter;


/**
 * Class Filter
 * @package Magein\form\library
 */
class Filter
{
    /**
     * string
     * @var string
     */
    private $error = '';

    /**
     * @param $error
     */
    protected function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 过滤标签、前后空格
     * @param $string
     * @return string
     */
    protected function toString($string)
    {
        return strip_tags(trim($string));
    }
}