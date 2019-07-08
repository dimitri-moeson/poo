<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 21:20
 */

namespace App\Model\Entity\Game;


trait StatsTrait
{

    /** @var int */
    protected $atk = 20 ;

    /** @var int  */
    protected $def = 20 ;

    public function __set($name, $value , $replace = false):self
    {
        if($replace)
            $this->{$name} = $value;
        else
            $this->{$name} += $value;


        if($this->{$name} < 0)
            $this->{$name} = 0;

        return $this;
    }


    /**
     * le personnage prend un bonus/malus
     * @param $stat
     * @param null $atk
     */
    public function buff($stat,$atk = null):self {

        if($this->deces()) return null;

        if(!is_null($atk) && $atk != 0 ){
            Journal::getInstance()->add( $this->name." ".( $atk > 0 ? "prend" : "perd" )." ".abs($atk)."[".$stat."]<br/>");
            call_user_func(array($this,"set".ucfirst($stat)),array($atk,false));
        }

        return $this ;
    }


    /**
     * @param int $atk
     * @param bool $replace
     * @return PersonnageEntity

    public function setAtk(int $atk, $replace = false):self
    {
        if($replace)
            $this->atk = $atk;
        else
            $this->atk += $atk;


        if($this->atk < 0)
            $this->atk = 0;

        return $this;
    }
*/
    /**
     * @return int
    public function getAtk()
    {
        return $this->atk;
    }
*/

    /**
     * @param int $def
     * @return StatsTrait

    public function setDef(int $def, $replace = false): self
    {
        if($replace)
            $this->def = $def;
        else
            $this->def += $def;


        if($this->def < 0)
            $this->def = 0;

        return $this;
    }*/

    /**
     * @return int
    public function getDef(): int
    {
        return $this->def;
    }
*/
}