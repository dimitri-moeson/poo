<?php


namespace App\Model\Comportement;

use App\Model\Heritage\Game\Inventaire\InventaireRessourceEntity;
use App\Model\Heritage\Game\Inventaire\InventaireStatEntity;
use App\Model\Entity\Game\Map\MapEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Object\Journal;
use Core\Debugger\Debugger;

Trait CombattantTrait
{
    /** @var string  */
    protected $status = "normal" ;

    /** @var int  */
    protected static $max_vie = 100 ;

    /**
     * @var InventaireRessourceEntity
     */
    protected $ressources;

    /**
     * @var InventaireStatEntity
     */
    protected $stats;

    /** @var int */
    protected $vie = 100;


    /**
     * @var
     */
    protected $position ;

    public function initCombattant(){

        $this->stats = new InventaireStatEntity();
        $this->ressources = new InventaireRessourceEntity();

    }
    /**
     * le personnage est-il decedé ?
     * @return bool
     */
    public function deces(){

        //echo $this->vie."/".$this->status;

        if(intval($this->getVie()) <= 0 || $this->status == "mort")
        {
            $this->setVie(0,true);
            $this->setStatus("mort");
            Journal::getInstance()->add( "<p>" .$this->name. " est mort.". "</p>");
            return true ;
        }
        else
        {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function vivant(){

        if($this->getVie() > 0)
        {
            Journal::getInstance()->add( "<p>" .$this->name. " est vivant.". "</p>");
            return true ;
        }
        else
        {
            //$this->setVie(0,true);
            return false;
        }
    }

    /**
     * le personnage prend un degat/soin.
     * @param null $vie
     */
    public function hit($vie = null){

        if($this->deces()) return null;

        if(!is_null($vie) && $vie != 0 ){
            Journal::getInstance()->add( $this->name." ".( $vie > 0 ? "prend" : "perd" )." ".abs($vie)." PV.<br/>");
            $this->setVie($vie,false);
        }

        return $this ;
    }

    /**
     * le personnage attaque une cible
     * on va chercher toutes les stats offensives de l'attaquant pour calculer son DSP
     * on va chercher toutes les stats defensives du defendeur pour calculer sa résistance
     * @param PersonnageEntity $cible
     *
     */
    public function attaque($cible): ?self{

        if($this->deces()) return null;
        if($cible->deces()) return null;

        Journal::getInstance()->add( $this->getName()." attaque ".$cible->getName().".<br/>");

        Debugger::getInstance()->add( $this->getName()." attaque ".$cible->getName().".<br/>");

        $atk = $this->getStats()->getScore("offensif");
        $def = $cible->getStats()->getScore("defensif");

        $degat = ($atk-$def);
        /**
         * $degat est positif, alors l'attaquant depasse le defenseur
         */
        if($degat > 0 ) {
            $cible->hit((-1 * $degat));
        }
        return $this ;

    }

    /**
     * @param null $mana
     * @return $this|null
     */
    public function charge($mana = null ):self{

        if($this->deces()) return null;

        if(!is_null($mana) && $mana != 0 ){
            Journal::getInstance()->add( $this->name." ".( $mana > 0 ? "prend" : "perd" )." ".abs($mana)." Mana.<br/>");
            //$this->setMana($mana,false);
            //$this->getRessources()->offsetGet()
        }

        return $this ;

    }

    /**
     * fonction random ...
     */
    public function crier(){

        if($this->deces()) return null;

        Journal::getInstance()->add( "LEEROY JENKIS !!<br/>");

        return $this ;
    }



    /**
     * @param int $vie
     * @param bool $replace
     * @return PersonnageEntity
     */
    public function setVie($vie, $replace = false):self
    {
        if($replace)
            $this->vie = $vie;
        else
            $this->vie += $vie;

        if($this->vie > self::$max_vie)
            $this->vie = self::$max_vie;

        if($this->vie < 0) {
            $this->vie = 0;
            $this->setStatus("mort");
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getVie()
    {
        return $this->vie;
    }

    /**
     * @param string $status
     * @return PersonnageEntity
     */
    public function setStatus(string $status):self
    {
        $this->status = $status;
        if($this->status == "mort"){
            $this->setVie(0,true);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return InventaireStatEntity
     */
    public function getStats(): InventaireStatEntity
    {
        return $this->stats;
    }

    /**
     * @return InventaireRessourceEntity
     */
    public function getRessources(): InventaireRessourceEntity
    {
        return $this->ressources;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return PersonnageEntity
     */
    public function setPosition(MapEntity $position)
    {
        $this->position = $position;
        return $this;
    }

}