<?php

namespace Magein\createform\library;

class FormError
{
    private static $instance;

    /**
     * @var string
     */
    private $error;

    /**
     * FormError constructor.
     * @throws \Exception
     */
    protected function __construct()
    {
        throw new \Exception('forbid operate');
    }

    /**
     * @throws \Exception
     */
    protected function __clone()
    {
        throw new \Exception('forbid operate');
    }

    /**
     * @return static
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param $error
     * @return string
     * @throws \Exception
     */
    public function setError($error)
    {
        if (!is_string($error)) {
            throw new \Exception('input a string');
        }

        return $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}