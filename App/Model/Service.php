<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 02/05/2019
 * Time: 06:24
 */
namespace App\Model ;

use App;

/**
 * Class Service
 * @package App\Model
 */
class Service
{
    const TYPE_RECETTE = "recette";
    const TYPE_SAC = "sac";
    const TYPE_QUEST = "quete";
    const TYPE_RES = "ressource";
    const TYPE_STAT = "statistique";
    const TYPE_EQUIP = "equipement";
    const TYPE_SPELL = "spell";

    const RUB_PERSO = "personnage";
    const RUB_OBJ = "objet";
    const RUB_EQUIP = "equipement";
    const RUB_QUEST = "quest";

    private static $instances = array();

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        $class = get_called_class();

        if( !isset( self::$instances[$class] ) || is_null(self::$instances[$class]) ) {

            self::$instances[$class] = new $class();
        }
        // else echo "recall $class<br/>";

        return  self::$instances[$class] ;
    }

    /**
     * Service constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @param $model_name
     */
    protected function loadModel($model_name){

        $model_part = explode("\\",$model_name);

        $this->{end($model_part)."Base"} = App::getInstance()->getTable($model_name);
    }

    /**
     * @param $service
     */
    protected function loadService($service ){

        $model_part = explode("\\",$service);

        $this->{end($model_part)."Service"} = App::getInstance()->getService($service);
    }

    /**
     * @param $string
     * @param string $delimiter
     * @return false|string|string[]|null
     */
    public function slugify($string, $delimiter = '-') {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}