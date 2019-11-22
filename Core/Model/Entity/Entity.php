<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:34
 */
namespace Core\Model\Entity ;

use Core\Debugger\Debugger;
use DateTime;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class Entity
 * @package Core\Model\Entity
 */
class Entity
{
    /**
     * @var int $id
     */
    protected $id ;

    protected $createAt ;
    protected $updateAt ;
    protected $deleteAt ;

    /**
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            $this->$name = $this->$method();
        }

        if (isset($this->$name))
            return $this->$name;
        /** ->getValue() */
    }

    /**
     * @param $val
     * @param $format
     * @return bool|DateTime
     */
    protected function getDateTime($val,$format){

        $value = DateTime::createFromFormat($format, $val);

        if ( ( $value instanceof DateTime) && $value->format($format) == $val) {

            return $value ;
        }

        return $val ;

    }

    /**
     * @param $name
     * @param $val
     */
    function __set($name, $val)
    {
        //echo "entity __set($name)<br/>";
        if( in_array( $name , array("createAt","updateAt","deleteAt") ))
        {
            $format = "Y-m-d H:i:s";

            $val = $this->getDateTime($val , $format );
        }

        $this->$name = $val;
    }

}

class OffEntity
{

    public $id;
    public $dateAdd;
    public $dateSet;
    public $dateDel;

    function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if(method_exists($this,$method)) {
            $this->$name = $this->$method();
        }
        elseif (strpos($name, "_id") !== false) {
            $name = str_replace('_id', '', $name);
        }
        elseif (strpos($name, 'date_') !== false) {
            $name = 'date' . ucfirst(str_replace('date_', '', $name));
        }
        if(isset($this->$name))
            return $this->$name ; /** ->getValue() */
    }
    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    function __set1($name, $val)
    {
        if (strpos($name, 'date_') !== false)
        {
            list($name, $value) = $this->getDateObj($name, $val);
        }
        elseif (strpos($name, "_id") !== false)
        {
            list($name, $value) = $this->getForeignObj($name, $val);
        }
        else
        {
            $value = $val ; //new Property($val);
        }
        $this->$name = $value;
    }

    /**
     * @param $name
     * @param $val
     */
    function __set2($name, $val)
    {
        if(is_int($val))
        {
            $this->$name = $val ;
        }

        if(is_string($val))
        {
            $format = "Y-m-d H:i:s";
            $value = DateTime::createFromFormat($format, $val);

            if ($value && $value->format($format) == $val) {
                $this->$name = $value;
            }
            else {

                $this->$name = $val ;
            }
        }
        /**elseif (strpos($name, "_id") !== false)
        {
        list($name, $value) = $this->getForeignObj($name, $val);
        }**/
        else
        {
            $this->$name = $val ;
        }
    }

    public function slugify($text)
    {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        setlocale(LC_ALL, $oldLocale);
        if (empty($text)) {
            $text = 'n-a';
        }
        $this->slug = $text;
        return $text;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return array
     */
    private function getDateObj($name, $value)
    {
        unset($this->{'date_' . $name});
        $name = 'date' . ucfirst(str_replace('date_', '', $name));
        if (!($value instanceof DateTime)) {
            $val = DateTime::createFromFormat("Y-m-d H:i:s", $value);
            //$value = new Property($val,"datetime");
            return [$name, $val];
        }
    }
    /**
     * @param $name
     * @param $value
     *
     * @return array
     */
    private function getForeignObj($name, $value)
    {
        if ($value !== null && $value !== 0) {
            unset($this->$name);
            $name = str_replace('_id', '', $name);
            $base = get_class($this);
            $exp_base = explode('\\', $base);
            $end_base = end($exp_base);
            $rpl_base = str_replace('Entity', '', $end_base);
            $lower = strtolower($rpl_base);
            $model = $this->referentiel($name, $lower);
            if (is_array($model)) {
                $ent = ucfirst($model["data"]) . '\\' . ucfirst($model["entity"]);
                $data = ucfirst($model["data"]);
            } else {
                $ent = ucfirst($model);
                $data = ucfirst($model);
            }
            $entity = "Model\Entity\\" . $ent . "Entity";
            $val = App::getInstance()->getData($data)->find($value, $entity);
            //$value = new Property($val,"object");
            return [$name, $val];
        }
    }
}