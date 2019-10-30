<?php
namespace Core\Render;

use Core\Path\Path;
use Core\Redirect\Redirect;
use Core\Request\Request;
use Core\Rewrite\Rewrite;

class Url
{
    public $slg ;
    public $act ;
    public $ctl ;
    public $dom = null  ;

    public $params = array();

    private $separator = "/";

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     * @return Url/Rewrite/Redirect
     */
    public static function generate($_act = null , $_ctl = null , $dom = null  , $slug = null ){

        //echo "<pre>".print_r( func_get_args(), 1 )."</pre>";

        $class = get_called_class();

        $obj = new $class($_act , $_ctl , $dom);

        if($obj instanceof Url)
        {
            if(!is_null($slug))
            {
                $obj->slg = $slug ;
            }
        }
        return $obj ;

    }

    /**
     * Url constructor.
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     */
    protected function __construct($_act = null , $_ctl = null , $dom = null , $slug = null  )
    {
        $this->act = $_act ?? Request::getInstance()->getAction();
        $this->ctl = $_ctl ?? Request::getInstance()->getCtrl();
        $this->dom = $dom ?? null ;
        $this->slg = $slug ?? null ;
    }

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     * @return bool|string
     */
    public function getPath($_act = null ,$_ctl = null ,$_dom = null )
    {
        $act = $_act ?? $this->getAct();
        $ctl = $_ctl ?? $this->getCtl();
        $dom = $_dom ?? $this->getDom();

        if( Path::getInstance()->testActionPath($act,$ctl,$dom))
        {
            $slug = $this->getSlg() ;

            $params = $this->getParams();
            $_parameters = (isset($params) && !empty($params) ?  "?".self::buildQuery($params) : '');

            $var =  $this->dom_Construct($dom).
                $this->ctl_Construct($ctl) .
                $this->slug_Construct($slug) .
                $this->act_Construct($act) . $_parameters ;

            return trim($var) !== "" ? $var : DIRECTORY_SEPARATOR ;
        }

        return "error...";

    }

    /**
     * @param array $params
     * @return string
     */
    function buildQuery( $params = array())
    {
        return http_build_query($params ?? $this->getParams() );
    }

    public function dom_Construct($dom= null){

        if (is_null($dom)) {

            return "";
        }
        if( Path::getInstance()->testDomPath($dom)) {

            $ctl = DIRECTORY_SEPARATOR . strtolower($dom);

            return $ctl;
        }
    }

    /**
     * @param null $_ctl
     * @param null $dom
     * @return string
     */
    public function ctl_Construct($_ctl = null)
    {
        if(strtolower($_ctl) == "default" || is_null($_ctl) ){

            return "";
        }

            $ctl = DIRECTORY_SEPARATOR . strtolower($_ctl);

            return $ctl;
    }

    /**
     * @param null $_act
     * @return string
     */
    public function act_Construct($_act = null){

        if(strtolower($_act) == "index" || is_null($_act) ){

            return "";
        }

        return DIRECTORY_SEPARATOR . $_act  ;

    }

    public function slug_Construct($_slg = null){

        if( is_null($_slg) ){

            return "";
        }
        return DIRECTORY_SEPARATOR . $_slg;
    }
    /**
     * @param mixed $ctl
     * @return Redirect
     */
    public function setCtl($ctl = null ): Url
    {
        $this->ctl = $ctl;
        return $this;
    }

    /**
     * @param mixed $act
     * @return Redirect
     */
    public function setAct($act = null ): Url
    {
        $this->act = $act ;
        return $this;
    }

    /**
     * @param mixed $dom
     * @return Redirect
     */
    public function setDom($dom = null ): Url
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCtl()
    {
        return $this->ctl;
    }

    /**
     * @return mixed
     */
    public function getAct()
    {
        return $this->act;
    }

    /**
     * @param array $params
     * @return Redirect
     */
    public function setParams(array $params = array()): Url
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return mixed|null
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * lock
     * @return bool|string
     */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * @param mixed $slg
     * @return Url
     */
    public function setSlg($slg)
    {
        $this->slg = $slg;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlg()
    {
        return $this->slg;
    }
}