<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 20:06
 */

namespace Core;


class Config
{
    private $settings = array();

    private static $_instance ;

    public static function getInstance($file)
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Config($file);
        }

        return self::$_instance;
    }

    private function __construct($file)
    {
        $this->settings = parse_ini_file($file);

    }

    public function get($key){

        return $this->settings[$key] ?? null ;

    }
}