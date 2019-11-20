<?php


namespace App\Model\Object\Game\Combat;

use App\Model\Object\Journal;
use App\Model\Entity\Game\Personnage\PersonnageEntity;

/**
 * Class Passe
 * @package App\Combat
 */
class Passe
{
	private $round ;
	private $perso ;

    private function __construct(Round $round, $perso = null ) {

        //Journal::getInstance()->add("nouvelle passe<br/>");

		$this->round = $round ;
		$this->perso = $perso;
    }

    public static function launch(Round $round, $perso = null){

        return new self( $round,  $perso );
    }

    /**
     * @return PersonnageEntity
     */
    public function getPerso()
    {
        return $this->perso;
    }

}