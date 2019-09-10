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

    const MODEL_DIR = ROOT."/App/Model";
    const  VIEW_DIR = ROOT."/App/View";
    const  CTRL_DIR = ROOT."/App/Controller";
    const  CORE_DIR = ROOT."/Core";
    const   TMP_DIR = ROOT."/tmp";

    /**
     * @param $file
     * @return Config
     */
    public static function getInstance($file)
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Config($file);
        }

        return self::$_instance;
    }

    /**
     * Config constructor.
     * @param $file
     */
    private function __construct($file)
    {
        $this->settings = parse_ini_file($file);

    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key){

        return $this->settings[$key] ?? null ;

    }
}