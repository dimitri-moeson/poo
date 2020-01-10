<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:17
 */
namespace App\Model\Entity\Blog ;

use App\Model\Comportement\PageTrait;
use Core\Model\Entity\Entity;

class ArticleEntity extends Entity
{
    use PageTrait;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $val =  parent::__get($name);

        return $val ;
    }

    /**
     * @param $name
     * @param $val
     */
    public function __set($name, $val)
    {
        parent::__set($name, $val);

        //echo "ArticleEntity __set($name)<br/>";

        if($name == "author_id") {

            $this->author = \App::getInstance()->getTable("User")->find($val);
            unset($this->$name);
        }

        if($name == "parent_id") {

            $this->parent = \App::getInstance()->getTable("Blog\Article")->find($val);
            unset($this->$name);

        }

        if($name == "date")
        {
            $forma2 = "Y-m-d";

            $valu2 = $this->getDateTime($val , $forma2 );

            //print_r($valu2);

            if (($valu2 instanceof DateTime)&& $valu2->format($forma2) == $val) {//$valu2 ) {

                $this->$name = $valu2 ;
            }

        }

        return $this ;
    }
}