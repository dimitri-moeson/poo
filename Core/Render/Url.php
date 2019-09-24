<?php
namespace Core\Render;

use Core\Redirect\Redirect;
use Core\Request\Request;
use Core\Rewrite\Rewrite;

class Url
{
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
    public static function generate($_act = null , $_ctl = null , $dom = null, $slug = null ){

        $class = get_called_class();

        $obj = new $class($_act , $_ctl , $dom);

        if($obj instanceof Url)
        {
            if(!is_null($slug))
            {
                $obj->setParams(array($slug));
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
    protected function __construct($_act = null , $_ctl = null , $dom = null )
    {
        $this->act = $_act ?? Request::getInstance()->getAction();
        $this->ctl = $_ctl ?? Request::getInstance()->getCtrl();
        $this->dom = $dom ?? null ;
    }

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     * @return bool
     */
    public function verif($_act = null ,$_ctl = null ,$dom = null )
    {
        $act = $_act ?? $this->getAct();

        $ctrl = $this->ctrl_Construct($_ctl ,$dom);

        if( Request::getInstance()->is_callable($ctrl,$act) )
        {
            return true;
        }

        return false;
    }

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     * @return bool|string
     */
    public function getPath($_act = null ,$_ctl = null ,$dom = null )
    {
        $act = $_act ?? $this->getAct();
        $ctrl = $this->ctrl_Construct($_ctl ,$dom);

        if( Request::getInstance()->is_callable($ctrl,$act) )
        {
            $slug = array_shift( $this->params);

            $params = $this->getParams();
            $_parameters = (isset($params) && !empty($params) ?  DIRECTORY_SEPARATOR."?".self::buildQuery($params) : '');

            return $this->dom_Construct($dom).
                $this->ctl_Construct($_ctl) .
                $this->slug_Construct($slug) .
                $this->act_Construct($act) . $_parameters ;


            /*if(REWRITE)
            {*/
                if(!is_null($slug))
                {
                    // -- $test = implode(DIRECTORY_SEPARATOR, $params);

                    return $this->dom_Construct($dom).$this->ctl_Construct($_ctl) . DIRECTORY_SEPARATOR . $slug . $this->act_Construct($act) . $_parameters ;
                }
                else
                {
                    return $this->dom_Construct($dom).$this->ctl_Construct($_ctl) . $this->act_Construct($act) . $_parameters ;
                }
           /* }
            else
            {
                return "/?p=" . $this->ctl_Construct($_ctl, $dom) . $this->act_Construct($act) . $_parameters ;
            }*/
/**

                $r = Rewrite::generate($act, $this->getCtl() , ($this->getDom() ?? "default") );
                $r->setParams($params);

                return $r->getRewrite() ;

 **/
        }

        return "error...";

    }

    /**
     * @param null $_ctl
     * @param null $dom
     * @return string
     */
    public function ctrl_Construct($_ctl = null , $dom = null )
    {
        $_ctrl =  $_ctl ?? $this->getCtl();

        if($dom)
        {
            $ctrl = '\App\Controller\\'.ucfirst($dom).'\\'.ucfirst($_ctrl).'Controller';
        }
        elseif($this->getDom() != null )
        {
            $ctrl = '\App\Controller\\'.ucfirst($this->getDom()).'\\'.ucfirst($_ctrl).'Controller';
        }
        elseif($_ctrl)
        {
            $ctrl = '\App\Controller\\'.ucfirst($_ctrl).'Controller';
        }
        else//if($_ctrl)
        {
            $ctrl = '\App\Controller\\'.ucfirst($_ctrl).'Controller';
        }

        return $ctrl ;
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

        if($dom)
        {
            $ctl = DIRECTORY_SEPARATOR .strtolower("".$dom."");
        }
        elseif($this->getDom() != null )
        {
            $ctl = DIRECTORY_SEPARATOR .strtolower($this->getDom());
        }

        return $ctl ;

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

        $_ctrl =  $_ctl ?? $this->getCtl();

        $ctl = DIRECTORY_SEPARATOR .strtolower($_ctrl);

        return $ctl ;
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
        $this->ctl = $ctl ?? $this->getCtl();
        return $this;
    }

    /**
     * @param mixed $act
     * @return Redirect
     */
    public function setAct($act = null ): Url
    {
        $this->act = $act ?? $this->getAct();
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
}