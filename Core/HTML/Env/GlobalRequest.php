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
     * @param $donnees
     * @return array|string
     */
    public function clean($donnees)
    {
        if (is_array($donnees))
        {
            foreach ($donnees as $n => $v)
            {
                $donnees[$n] = $this->clean($v);
            }
        }
        else
        {
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            $donnees = htmlentities($donnees);
            //$donnees = mysqli_real_escape_string(null, $donnees);
        }
       //echo "clean(out):".print_r($donnees,1)."<br/>";
        return $donnees;
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
        // edition
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

        // retour de la val
        if (self::has($key)) {

            if ($this->call == "Post") {

                return $this->clean($_POST[$key]) ?? null;

            } elseif ($this->call == "Get") {

                return $this->clean($_GET[$key]) ?? null;

            } else {

                return $this->clean($_REQUEST[$key]) ?? null;

            }
            return;
        }
    }

    /**
     * @return mixed
     */
    public function content(){

        if ($this->call == "Post") {
            return $this->clean($_POST) ;
        } elseif ($this->call == "Get") {
            return $this->clean($_GET) ;
        } else {
            return $this->clean($_REQUEST);
        }
    }

    /**
     * confirme l'envoie du formulaire
     * @return bool
     */
    public function submit()
    {
        $called = strtoupper($this->call);
        $method = strtoupper(getenv('REQUEST_METHOD'));

        if ( $method === $called){

            return self::$instances[ $this->call ]->submit();

        }
    }
}