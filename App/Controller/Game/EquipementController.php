<?php


namespace App\Controller\Game;


use App\Model\Service\EquipementService;
use Core\HTML\Env\Post;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class EquipementController extends AppController
{
    /**
     * EquipementController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loadService("equipement");

        if ($this->EquipementService instanceof EquipementService) {
            $this->EquipementService->setPersonnage($this->legolas);

        }
    }

    /**
     * @param $place
     */
    public function retire($place){

        if (Post::getInstance()->has('change', 'post')) {
            $equip = $this->EquipementService->getRangeable($place);
            $this->EquipementService->retire($equip);
            FlashBuilder::create($this->legolas->getName() . " change d'equipement " . $equip->getName(), "success");

            Redirect::getInstance()->setAct("index")->setCtl("default")->setDom("game")->send();
        }
    }

    /**
     * @param $place
     */
    public function ajoute($place){

        if (Post::getInstance()->has('change', 'post')) {
            $equip = $this->EquipementService->getEquipable($place);
            $this->EquipementService->equip($equip);
            FlashBuilder::create($this->legolas->getName() . " change d'equipement " . $equip->getName(), "success");

            Redirect::getInstance()->setAct("index")->setCtl("default")->setDom("game")->send();
        }
    }

    /**
     * @param $arme
     */
    public function arme($arme){
        if (Post::getInstance()->has('change', 'post')) {
            $equip = $this->EquipementService->getArmable($arme);
            $this->EquipementService->arme($equip);

            FlashBuilder::create($this->legolas->getName() . " change d'equipement " . $equip->getName(), "success");

            Redirect::getInstance()->setAct("index")->setCtl("default")->setDom("game")->send();
        }

    }
}