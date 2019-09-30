<?php


namespace Core\Path;

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

        return false ;
    }

    /**
     * @param string $ctl
     * @param null $dom
     * @return string
     */
    public function testControlPath($ctl = "default",$dom = null){

            if(!is_null($dom) && !is_null($ctl))    $ctr_path_a = '\App\Controller\\' . ucfirst(strtolower($dom)) . '\\' . ucfirst(strtolower($ctl)) . 'Controller';
        elseif( is_null($dom) && !is_null($ctl))    $ctr_path_b = '\App\Controller\\' . ucfirst(strtolower($ctl)). 'Controller';
        elseif(!is_null($dom) &&  is_null($ctl))    $ctr_path_c = '\App\Controller\\' .  ucfirst(strtolower($dom)) . '\DefaultController';
        elseif( is_null($dom) &&  is_null($ctl))    $ctr_path_d = '\App\Controller\DefaultController';
        else                                        $ctr_path_e = '\App\Controller\DefaultController';

            if( isset($ctr_path_a) && class_exists($ctr_path_a,true))
            {
                return $ctr_path_a ;
            }
        elseif( isset($ctr_path_b) && class_exists($ctr_path_b,true))
            {
                return $ctr_path_b ;
            }
        elseif( isset($ctr_path_c) && class_exists($ctr_path_c,true))
            {
                return $ctr_path_c ;
            }
        elseif( isset($ctr_path_d) && class_exists($ctr_path_d,true))
            {
                return $ctr_path_d ;
            }
        elseif( isset($ctr_path_e) && class_exists($ctr_path_e,true))
            {
                return $ctr_path_e ;
            }
    }

    /**
     * @param $request
     */
    public function testRequestPath($request){
        //...
    }
}