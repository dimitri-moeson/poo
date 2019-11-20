<?php

namespace App\Controller\Game;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Service\EquipementService;
use App\Model\Service\MapService;
use App\Model\Service\MovementService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\Debugger\Debugger;
use Core\HTML\Env\Get;
use Core\HTML\Form\Form;
use Core\Render\Render;

/**
 * Class DefaultController
 * @package App\Controller\Game
 *
 * /game/
 * /game/movement
 * /game/equipement/retire
 * /game/equipement/ajoute
 */
class DefaultController extends AppController
{
    /**
     * TestController constructor. appel des service/table
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->ctrLog()) {

            Render::getInstance()->setTemplate('default');

            $this->loadService("Item");
            $this->loadService("Map");
            $this->loadService("Movement");
            $this->loadService("Quest");

        }
    }

    /**
     *
     */
    private function equipement()
    {
        if ($this->EquipementService instanceof EquipementService) {
            $this->EquipementService->setPersonnage($this->legolas);

            $this->equipables = $this->EquipementService->listEquipable(Get::getInstance()->val('place'));
        }


    }

    /**
     *
     */
    private function inventaire()
    {
        if ($this->Inventaire instanceof InventaireTable) {

            $e_types = Get::getInstance()->has('place') ? array(Get::getInstance()->val('place')) : null;
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id, "personnage", "sac", $e_types, ItemEntity::class);
        }
    }

    /**
     *
     */
    private function map()
    {
        if ($this->MapService instanceof MapService) {

            $this->MapService->setPersonnage($this->legolas);

            if ($this->MovementService instanceof MovementService)
                $this->MovementService->setPersonnage($this->legolas);

            $this->alentours = $this->MapService->arround();

            $this->form = new Form();
        }
    }

    /**
     *
     */
    public function index()
    {
        Debugger::getInstance()->perso($this->legolas);

        $this->equipement();

        $this->inventaire();

        $this->map();

        //echo " dom : none cntl : default action :  index perso : ";
        //var_dump($this->legolas);

        Render::getInstance()->setView("Game/Index");
    }
}