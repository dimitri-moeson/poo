<?php

namespace Core\HTML\Env;


class GlobalRequest
{
    private static $instances = array();

    private $call;

    /**
     * @return GlobalRequest
     */
    public static function getInstance():GlobalRequest
    {
        $class = get_called_class();

        if (!isset(self::$instances[$class]) || is_null(self::$instances[$class]))
        {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }

    /**
     * Service constructor.
     */
    protected function __construct()
    {
        $this->call = str_replace(__NAMESPACE__ . '\\', '', get_called_class());
    }

    /**
     * test l'existence de la variable
     * @param $key
     * @return bool
     */
    public function has(string $key = "index")
    {
        if ($this->call == "Post") {
            return isset($_POST[$key]) && !empty($_POST[$key]);
        } elseif ($this->call == "Get") {
            return isset($_GET[$key]) && !empty($_GET[$key]);
        } else
            return isset($_REQUEST[$key]) && !empty($_REQUEST[$key]);

        return false;
    }

    /**
     * Si value est renseigné, change le contenue de Key
     * retourne la valueur de key
     * @param $key
     * @param null $value
     * @return mixed
     */
    public function val(string $key = "index", $value = null)
    {
        if (!is_null($value))
        {
            if ($this->call == "Post") {

                $_POST[$key] = $value;

            } elseif ($this->call == "Get") {

                $_GET[$key] = $value;

            } else {

                $_REQUEST[$key] = $value;
            }
            return;
        }

        if (self::has($key)) {

            if ($this->call == "Post") {

                return $_POST[$key] ?? null;

            } elseif ($this->call == "Get") {

                return $_GET[$key] ?? null;

            } else {

                return $_REQUEST[$key] ?? null;

            }
            return;
        }
    }

    /**
     * @return mixed
     */
    public function content(){

        if ($this->call == "Post") {
            return $_POST ;
        } elseif ($this->call == "Get") {
            return $_GET ;
        } else {
            return $_REQUEST;
        }
    }
}