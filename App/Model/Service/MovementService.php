<?php


namespace App\Model\Service;


use App\Model\Entity\Game\Map\MapEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Journal;
use App\Model\Service;
use Core\Redirect\Redirect;

class MovementService extends Service
{
    /**
     * @var PersonnageEntity
     */
    private $personnage;

    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Personnage\Personnage");

        $this->loadService("Personnage");

    }
    /**
     * @param PersonnageEntity $personnage
     */
    public function setPersonnage(PersonnageEntity $personnage): void
    {
        $this->personnage = $personnage;
    }

    function move(MapEntity $map)
    {
        $this->personnage->setPosition($map);
        $this->personnage->record = true ;

        Journal::getInstance()->add( $this->personnage->getName() . " se deplace vers [" . $map->x . ":". $map->y."]<br/>");

        if($this->PersonnageService instanceof PersonnageService) {
            $this->PersonnageService->save($this->personnage);
        }
        $random = rand(1,100);

        if($random<=25){
            // declenche un combat aleatoire
            Redirect::getInstance()->setAct("combat")->setCtl("test")->send();
        }

        return true ;
    }

}