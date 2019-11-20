<?php


namespace App\Model\Comportement;


use App\Model\Entity\Game\Personnage\PersonnageEntity;

trait CiblePersonnageTrait
{


    /**
     * @param PersonnageEntity $cible
     * @param bool $defense
     */
    public function affecte(PersonnageEntity $cible , $defense = false ){

        switch($this->type){

            case "soin" :

                $cible->hit($this->value);
                break;
            case "res" :

                if($cible->deces()) {
                    $cible->setVie($cible->getMaxVie(), true);
                    $cible->setStatus("normal");
                }

                break;

            case "tue" :

                if(!$cible->deces()) {
                    $cible->setVie(0, true);
                    $cible->setStatus("mort");
                }
                break;

            case "ressource" :

                $cible->charge($this->value);
                break;

            case "poison" :

                //if($cible->getDef() > $this->value) {
                Journal::getInstance()->add( $cible->getName()." est empoisonné<br/>");
                $cible->setStatus("poison");
                //}
                break;

            case "buff" :

                if($defense) {
                    $cible->buff($this->value);
                }
                break ;

        }
    }



}