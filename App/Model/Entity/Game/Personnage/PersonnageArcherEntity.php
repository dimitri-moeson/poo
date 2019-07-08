<?php
namespace App\Model\Entity\Game\Personnage ;

use App\Model\Entity\Journal;

/**
 * Class PersonnageEntityArcher
 * @package App\PersonnageEntity
 */
class PersonnageArcherEntity extends PersonnageEntity
{
    protected static $max_vie = 40 ;

    protected $vie = 40 ;

    public function __construct()
    {
        parent::__construct();
        self::$max_vie = 40 ;
        $this->vie = 40;
    }

    /**
     * Passif de classe : les archers critique à 2 automatiquement
     * @param PersonnageEntity $cible
     */
    public function attaque(PersonnageEntity $cible): ?PersonnageEntity {

        Journal::getInstance()->add( 'Passif de classe : les archers attaque à 2 reprise.<br/>');
        parent::attaque($cible);
        parent::attaque($cible);
        Journal::getInstance()->add( 'le passif coute 1 mana.<br/>');
        parent::charge(-1 );
        return $this ;

    }
}