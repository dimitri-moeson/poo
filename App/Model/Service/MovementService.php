<?php


namespace App\Model\Service;


use App\Model\Entity\Game\Map\MapEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Heritage\Game\Item\ItemStrumEntity;
use App\Model\Object\Combat\Defi;
use App\Model\Object\Journal;
use App\Model\Service;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

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
        $this->loadService("Monstre");

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

        if($random<=50){
            // declenche un combat aleatoire
            $this->randomFight($random);
            Redirect::getInstance()->setCtl('fight')->setDom("game")->setAct("index")->send();
        }

        return $random ;
    }

    /**
     * @param $random
     */
    private function randomFight($random){

        if($this->MonstreService instanceof MonstreService )
        {
            $strum = $this->MonstreService->getFightable();

            if($strum instanceof ItemStrumEntity) {
                $strum->setPosition($this->personnage->getPosition());

                $defi = new Defi(array($this->personnage, $strum));

                $_SESSION['defi'] = serialize($defi);
                FlashBuilder::create("combat aléatoire lancé($random)", "success");
            }
        }
    }

}