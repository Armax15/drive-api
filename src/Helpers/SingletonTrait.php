<?php


namespace core\Helpers;


trait SingletonTrait
{

    private static self $instance;

    private function __construct(){}
    private function __clone(){}
    private function __sleep(){}

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new self(...func_get_args());
        }

        return static::$instance;
    }
}