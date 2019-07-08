<?php
namespace App;

/**
 * Class Format
 * @package App
 */
class Format
{

    const PREFIX = 0 ;

    public static function with0($num) {

        return str_pad($num,2,self::PREFIX,STR_PAD_LEFT);
    }


}