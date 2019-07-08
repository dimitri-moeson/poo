<?php
namespace Core\Redirect;

use Core\HTML\Env\Get;
use Core\Request\Request;

class Redirect
{
    static function reload(){

        header("location:?".http_build_query(Get::getInstance()->content('s')));

    }

    static function redirect($_act = null ,$_ctl = null ,$dom = null ){

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

        if( Request::getInstance()->is_callable($ctrl,$act) ){

            header("location:?p=".$_ctrl.".".$_act);
            die;

        }

    }
}