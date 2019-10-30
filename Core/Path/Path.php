<?php


namespace Core\Path;

use Core\Config;

class Path
{
    /**
     * @var Path
     */
    private static $instance;

    /**
     * singleton
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance ;
    }

    /**
     * Request constructor.
     * singleton
     */
    private function __construct()
    {

    }

    /**
     * @param string $act
     * @param string $ctl
     * @param null $dom
     * @return bool
     */
    public function testActionPath($act = "index",$ctl = "default",$dom = null)
    {
        $_ctl = $this->testControlPath($ctl,$dom);

        if(method_exists($_ctl, $act)  && is_callable(array($_ctl, $act)) )
        {
            return true;
        }
        //echo "testControlPath not true....";
        return false ;
    }

    /**
     * @param null $dom
     * @return bool
     */
    public function testDomPath($dom=null){

        if(!is_null($dom)) {
            $dom_path = ROOT . DIRECTORY_SEPARATOR
                . 'App' . DIRECTORY_SEPARATOR
                . 'Controller' . DIRECTORY_SEPARATOR
                . ucfirst(strtolower($dom)) . DIRECTORY_SEPARATOR;

            //echo $dom_path ;

            if (isset($dom_path) && file_exists($dom_path) && is_dir($dom_path)) {
                //echo "testDomPath true....";
                return true;
            }
        }
        //echo "testDomPath not true....";

        return false ;
    }

    /**
     * @param string $ctl
     * @param null $dom
     * @return string
     */
    public function testControlPath($ctl = "default",$dom = null){

        if($this->testDomPath($dom))
        {
                if(!is_null($dom) && !is_null($ctl))        $ctr_path['a'] = '\App\Controller\\' . ucfirst(strtolower($dom)) . '\\' . ucfirst(strtolower($ctl)) . 'Controller';
                elseif(!is_null($dom) &&  is_null($ctl))    $ctr_path['c'] = '\App\Controller\\' .  ucfirst(strtolower($dom)) . '\DefaultController';
        }
        else
        {
                if( is_null($dom) && !is_null($ctl))    $ctr_path['b'] = '\App\Controller\\' . ucfirst(strtolower($ctl)). 'Controller';
            elseif( is_null($dom) &&  is_null($ctl))    $ctr_path['d'] = '\App\Controller\DefaultController';
            //else                                        $ctr_path['e'] = '\App\Controller\DefaultController';

        }

        foreach ($ctr_path as $l => $path)
        {
            if( isset($path) && class_exists($path,true))
            {
                //echo "[[$l]]";
                return $path ;
            }
        }
        return false ;
    }

    /**
     * @param string $ctl
     * @param null $dom
     * @return bool|int|string
     */
    public function indexControlPath($ctl = "default",$dom = null){

        if($this->testDomPath($dom))
        {
                if(!is_null($dom) && !is_null($ctl))    $ctr_path['a'] = '\App\Controller\\' . ucfirst(strtolower($dom)) . '\\' . ucfirst(strtolower($ctl)) . 'Controller';
            elseif(!is_null($dom) &&  is_null($ctl))    $ctr_path['c'] = '\App\Controller\\' .  ucfirst(strtolower($dom)) . '\DefaultController';
        }
        else
        {
                if( is_null($dom) && !is_null($ctl))    $ctr_path['b'] = '\App\Controller\\' . ucfirst(strtolower($ctl)). 'Controller';
            elseif( is_null($dom) &&  is_null($ctl))    $ctr_path['d'] = '\App\Controller\DefaultController';
            //else                                        $ctr_path['e'] = '\App\Controller\DefaultController';

        }

        foreach ($ctr_path as $l => $path)
        {
            if( isset($path) && class_exists($path,true))
            {
                return $l ;
            }
        }

        return false ;
    }

    /**
     * @param $request
     */
    public function testRequestPath($request){
        //...
    }
}