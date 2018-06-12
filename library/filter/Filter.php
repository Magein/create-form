<?php

namespace Magein\createForm\library\filter;

use Magein\createForm\library\FormError;

/**
 * Class Filter
 * @package Magein\form\library
 */
class Filter
{
    use FormError;

    public function __construct()
    {
        $this->initErrors();
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