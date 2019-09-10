<?php
namespace Core\Rewrite;

use Core\Render\Url;

/**
 * Class Rewrite
 * @package Core\Rewrite
 */
class Rewrite extends Url
{
    /**
     * @var array
     */
    public $test = array(

        "admin" => array(),
         "blog"=> array(

             "article" => array(

                 "index" => "blog",
                 "categorie" => "categorie-",
                 "show" => "article-",
             )
         ),
        "game",
        "default" => array(

            "test" => array(

                "fiche" => "feuille-personnage"

            ),
            "default"

        ),
    );

    /**
     * @return mixed|string
     */
    function getRewrite(){

        $dom = $this->getDom();

        if( $dom == "default" || is_null($dom)){

            if(isset($this->test["default"][ $this->getCtl() ][ $this->getAct() ]))
            {
                return $this->test["default"][ $this->getCtl() ][ $this->getAct() ].self::query().".html" ;
            }
            else
            {
                return "?p=".$this->getCtl().".".$this->getAct() ;
            }
        }

        if(isset($this->test[ $dom ][ $this->getCtl() ][ $this->getAct() ]))
        {
            return $this->test[ $dom ][ $this->getCtl() ][ $this->getAct() ].self::query().".html";
        }
        else
        {
            return "?p=".$dom.".".$this->getCtl().".".$this->getAct() ;
        }
    }

    /**
     * query builder
     * @param array $params
     * @return string
     */
    function query( $params = array())
    {
        $par = $params ?? $this->getParams();

        return http_build_query( $par,null,"-" );
    }

    /**
     * @return bool|mixed|string
     */
    public function __toString()
    {
        $v = $this->getRewrite();

        return $v ;
    }

    /**
     * @param array $test
     * @return Rewrite
     */
    public function setTest(array $test): Rewrite
    {
        $this->test = $test;
        return $this;
    }
}