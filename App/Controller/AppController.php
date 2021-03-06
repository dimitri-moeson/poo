<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:07
 */
namespace App\Controller;

use App;
use App\Model\Table\Blog\ArticleTable;
use Core\Auth\DatabaseAuth;
use Core\Config;
use Core\Controller\Controller;
use Core\HTML\Header\Header;
use Core\Render\Render;
use Exception;

class AppController extends Controller
{
    /**
     * @var DatabaseAuth
     */
    protected $auth;

    /**
     * AppController constructor.
     */
    public function __construct()
    {

        Render::getInstance()->setTemplate('default');
        Render::getInstance()->setViewPath( realpath(Config::VIEW_DIR."/Html/"));

        Header::getInstance()->add_js("tooltip");
        Header::getInstance()->add_css("blade");

        $this->loadModel("Blog\Article");

        if($this->Article instanceof ArticleTable)
            $this->menu = $this->Article->findBy( ["type" => "page" ], ["position" => "desc"] );//allOf("page");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
    }

    /**
     * @param $model_name
     */
    protected function loadModel($model_name){

        $model_part = explode("\\",$model_name);

        $this->{end($model_part)} = App::getInstance()->getTable($model_name);
    }

    /**
     * @param $service
     */
    protected function loadService($service ){

        $model_part = explode("\\",$service);

        $name = end($model_part)."Service";

        try
        {
            $this->{$name} = App::getInstance()->getService($service);
        }
        catch (Exception $e)
        {
            var_dump($e);
            throw $e ;
        }
    }

    /**
     * @return bool
     */
    protected function ctrLog(){

        if(!$this->auth->logged()){

            $this->forbidden();
        }

        return true ;
    }
}