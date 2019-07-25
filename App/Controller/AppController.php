<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:07
 */
namespace App\Controller;

use App;
use Core\Controller\Controller;
use Core\HTML\Header\Header;
use Core\Render\Render;

class AppController extends Controller
{
    /**
     * AppController constructor.
     */
    public function __construct()
    {
        Render::getInstance()->setTemplate('default');
        Render::getInstance()->setViewPath( realpath(ROOT."/App/View/Html/"));

        Header::getInstance()->add_js("tooltip");
        Header::getInstance()->add_css("blade");
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

        try{
            $this->{$name} = App::getInstance()->getService($service);

        }catch (\Exception $e){

            var_dump($e);
            throw $e ;
        }
    }
}