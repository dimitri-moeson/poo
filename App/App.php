<?php

use App\Controller\AppController;
use App\Service;
use Core\Controller\Controller;
use Core\Database\MysqlDatabase;
use Core\Debugger\Debugger;
use Core\Render\Render;
use Core\Request\Request;
use Core\Session\Session;
use Core\Model\Table\Table;
use Core\Config;

class App
{
    /**
     * @var MysqlDatabase $database
     */
    private static $database;

    /**
     * @var App $_instance
     */
    private static $_instance;

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }

        return self::$_instance;
    }

    /**
     * App constructor.
     */
    private function __construct()
    {

    }

    /**
     * @brief inclusion des autoloader - session - debugger
     */
    public function load()
    {

        require ROOT . '/App/Autoloader.php';
        App\Autoloader::register();

        require ROOT . '/Core/Autoloader.php';
        Core\Autoloader::register();

        if (DEBUG) {
            putenv("DEBUG='1'");
            apache_setenv("DEBUG", "1");
        }

        Session::getInstance();

        return $this;
    }

    /**
     * @brief execute la request utilisateur.
     * @return $this
     */
    public function exec()
    {
        $request = Request::getInstance();

        if ($request->is_callable()) {

            echo "callable" ;

            $ctrl_name = $request->getCtrlName();
            $action = $request->getAction();
            $slug = $request->getSlug();

            Debugger::getInstance()->app('controller', $ctrl_name);
            Debugger::getInstance()->app('action', $action);
            Debugger::getInstance()->app('slug', $slug);

            $ctrl = new $ctrl_name();

            if ($ctrl instanceof AppController) {

                echo "instanceof AppController";
                call_user_func_array(array($ctrl, $action),array($slug));

                Render::getInstance($request->getPage())->exec($ctrl);
            }

            if (DEBUG) {
                Debugger::getInstance()->view();
            }
        } else {
            $ctrl = new Controller();
            $ctrl->notFound("call:" . $request->getCtrlName() . "(".$request->getCtrl().")->" . $request->getAction());
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDb(): MysqlDatabase
    {
        if (is_null(self::$database)) {

            $config = Config::getInstance(ROOT . "/Config/config.ini");

            self::$database = new MysqlDatabase(

                $config->get("db_name"),
                $config->get("db_type"),
                $config->get("db_user"),
                $config->get("db_pass"),
                $config->get("db_host")
            );
        }
        return self::$database;
    }

    /**
     * Factory
     * @param $name
     * @return mixed
     */
    public function getTable($name): Table
    {
        $ord = explode("\\", $name);
        $ord = array_map('ucfirst', $ord);
        $name = implode("\\", $ord);

        $class_name = "\App\Model\Table\\" . $name . "Table";

        return new $class_name($this->getDb());
    }

    /**
     * @param $service
     * @return Service
     */
    public function getService($service)
    {
        $ord = explode("\\", $service);
        $ord = array_map('ucfirst', $ord);
        $service = implode("\\", $ord);

        $var = "\App\Model\Service\\" . ucfirst(strtolower($service)) . "Service";

        try {
            return $var::getInstance();
        } catch (Exception $e) {
            throw $e;
        }

    }
}