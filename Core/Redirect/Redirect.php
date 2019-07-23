<?php
namespace Core\Redirect;

use Core\HTML\Env\Get;
use Core\Request\Request;

class Redirect
{
    public $act ;
    public $ctl ;
    public $dom = null  ;

    public $params = array();

    public static function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Redirect constructor.
     */
    private function __construct()
    {
        $this->ctl = Request::getInstance()->getCtrl();
        $this->act = Request::getInstance()->getAction();
    }

    /**
     *
     */
    static function reload()
    {
        self::location( http_build_query(Get::getInstance()->content('s')) );
    }

    static function buildQuery( $params = array())
    {
        $query = http_build_query($params);
    }

    /**
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     */
    static function redirect($_act = null ,$_ctl = null ,$dom = null )
    {
        $_ctrl =  $_ctl ?? Request::getInstance()->getCtrl();

        if($dom)
        {
            $ctrl = '\App\Controller\\'.ucfirst($dom).'\\'.ucfirst($_ctrl).'Controller';
        }
        elseif($_ctrl)
        {
            $ctrl = '\App\Controller\\'.ucfirst($_ctrl).'Controller';
        }

        $act = $_act ?? Request::getInstance()->getAction();

        if( Request::getInstance()->is_callable($ctrl,$act) )
        {
            self::location("p=".$_ctrl.".".$_act);
        }
    }

    /**
     * @param $url
     */
    private static function location($url)
    {
        header("location:?".$url);
        die;
    }

    public function send()
    {
        $_ctrl = is_null($this->dom) ? $this->getCtl() : $this->dom.".".$this->getCtl() ;
        $_act = $this->getAct();
        $params = $this->getParams();

        $url = "p=".$_ctrl.".".$_act."&".self::buildQuery($params);

        if(!is_null($this->dom))
        {
            $ctrl = '\App\Controller\\'.ucfirst($this->dom).'\\'.ucfirst($this->getCtl()).'Controller';
        }
        elseif($_ctrl)
        {
            $ctrl = '\App\Controller\\'.ucfirst($this->getCtl()).'Controller';
        }

        if( Request::getInstance()->is_callable($ctrl,$_act) )
        {
            self::location($url);
        }

        return false ;
    }

    /**
     * @param mixed $ctl
     * @return Redirect
     */
    public function setCtl($ctl = null ): Redirect
    {
        $this->ctl = $ctl ?? Request::getInstance()->getCtrl();
        return $this;
    }

    /**
     * @param mixed $act
     * @return Redirect
     */
    public function setAct($act = null ): Redirect
    {
        $this->act = $act ?? Request::getInstance()->getAction();
        return $this;
    }

    /**
     * @param mixed $dom
     * @return Redirect
     */
    public function setDom($dom = null ): Redirect
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
    public function setParams(array $params): Redirect
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
}