<?php


namespace App\Model\Heritage\Game\Personnage;


use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Object\Journal;

/**
 * Class PersonnageVoleurEntity
 * @package App\Model\Entity\Game\Personnage
 */
class PersonnageVoleurEntity extends PersonnageEntity
{
    public function attaque(PersonnageEntity $cible): ?PersonnageEntity {

        Journal::getInstance()->add( 'Passif de classe : les voleur pillent leur cible.<br/>');
        parent::attaque($cible);
        $item = $cible->getInventaire()->getRand();
        if($item) {
            $cible->retire($item);
            $this->ajoute($item);
            Journal::getInstance()->add('le passif coute 1 mana.<br/>');
            parent::charge(-1);
        }
        return $this ;

    }
}