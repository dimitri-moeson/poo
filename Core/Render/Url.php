<?php


namespace Core\Render;


use Core\Redirect\Redirect;
use Core\Request\Request;

class Url
{
    public $act ;
    public $ctl ;
    public $dom = null  ;

    public $params = array();

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     * @return Url
     */
    public static function generate($_act = null , $_ctl = null , $dom = null){

        return new self($_act , $_ctl , $dom);
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
        $params = $this->getParams();

        if( Request::getInstance()->is_callable($ctrl,$act) )
        {
            echo "aa";
            return $this->ctl_Construct($_ctl ,$dom).".".$act.( isset($params) && !empty($params) ? "&".self::buildQuery($params) : '') ;
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

        return $ctrl ;
    }

    function buildQuery( $params = array())
    {
        return http_build_query($params ?? $this->getParams() );
    }
    /**
     * @param null $_ctl
     * @param null $dom
     * @return string
     */
    public function ctl_Construct($_ctl = null , $dom = null )
    {
        $_ctrl =  $_ctl ?? $this->getCtl();

        if($dom)
        {
            $ctl = strtolower($dom).'.'.strtolower($_ctrl);
        }
        elseif($this->getDom() != null )
        {
            $ctl = strtolower($this->getDom()).'.'.strtolower($_ctrl);
        }
        elseif($_ctrl)
        {
            $ctl = strtolower($_ctrl);
        }

        return $ctl ;
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
     * @return mixed
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->getPath();
    }
}