<?php

namespace App\Controller\Game;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service\EquipementService;
use App\Model\Service\MapService;
use App\Model\Service\MovementService;
use App\Model\Service\PersonnageService;
use App\Model\Service\UserService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\Auth\DatabaseAuth;
use Core\Debugger\Debugger;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

class DefaultController extends AppController
{
    /**
     * TestController constructor. appel des service/table
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());

        $this->loadService("User");

        if($this->ctrLog()) {

            Render::getInstance()->setTemplate('default');

            $this->loadModel("Game\Personnage\Personnage");
            $this->loadModel("Game\Item\Item");
            $this->loadModel("Game\Inventaire\Inventaire");

            $this->loadService("Item");
            $this->loadService("Combat");
            $this->loadService("Map");
            $this->loadService("Personnage");
            $this->loadService("Equipement");
            $this->loadService("Inventaire");
            $this->loadService("Movement");
            $this->loadService("Monstre");
            $this->loadService("Quest");


            if ($this->PersonnageService instanceof PersonnageService) {
                $this->legolas = $this->PersonnageService->recup($this->auth->getUser('id'));
            }

            if (!$this->legolas){

                if ($this->UserService instanceof UserService) {
                    $this->UserService->initPerso($this->auth->getUser('id'));
                    Redirect::getInstance()->setCtl("inscription")->setAct("faction")->send();
                }

                //$this->notFound("personnage");
            }

            if ($this->Inventaire instanceof InventaireTable)
                $this->sacoche = $this->Inventaire->itemListing($this->legolas->id, "personnage", "sac", null, ItemEntity::class);
        }
    }

    private function equipement()
    {

        if($this->EquipementService instanceof EquipementService)
        {
            $this->EquipementService->setPersonnage($this->legolas);

            if (Post::getInstance()->has('change', 'post')) {

                $type = Post::getInstance()->val('change');

                if (Post::getInstance()->has('retire')) {
                    $equip = $this->EquipementService->getRangeable(Post::getInstance()->val('place'));
                    $this->EquipementService->retire($equip);
                }

                if (Post::getInstance()->has('equip')) {
                    $equip = $this->EquipementService->getEquipable(Post::getInstance()->val('place'));
                    $this->EquipementService->equip($equip);
                }

                if (Post::getInstance()->has('arme')) {
                    $equip = $this->EquipementService->getArmable(Post::getInstance()->val('arme'));
                    $this->EquipementService->arme($equip);
                }

                //$this->viewText = $this->legolas->getName() . " change d'equipement " . $equip->getName() . "<br/>";
                //Redirect::reload();

                FlashBuilder::create( $this->legolas->getName() . " change d'equipement " . $equip->getName(),"success");

                Redirect::getInstance()->setAct("fiche")->send();

            }

            $this->equipables = $this->EquipementService->listEquipable(Get::getInstance()->val('place'));
        }


    }

    private function inventaire()
    {
        if($this->Inventaire instanceof InventaireTable ) {

            $e_types = Get::getInstance()->has('place')  ? array(Get::getInstance()->val('place')) : null ;
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id, "personnage", "sac", $e_types , ItemEntity::class);
        }
    }

    private function map()
    {
        if($this->MapService instanceof MapService) {

            $this->MapService->setPersonnage($this->legolas);

            if( $this->MovementService instanceof MovementService)
                $this->MovementService->setPersonnage($this->legolas);

            if (Post::getInstance()->has('move')) {
                if ($this->legolas instanceof PersonnageEntity) {
                    $select = explode("|", Post::getInstance()->val("coordonnees"));

                    $coord = array(

                        'x' => $this->legolas->getPosition()->x + ($select[1] == "e" ? 1 : ($select[1] == "w" ? -1 : 0)),
                        'y' => $this->legolas->getPosition()->y + ($select[0] == "s" ? 1 : ($select[0] == "n" ? -1 : 0)),

                    );

                    $pos = $this->MapService->search($coord);

                    if( $this->MovementService instanceof MovementService)
                        $this->MovementService->move( $pos);

                    //$this->viewText = $this->legolas->getName() . " se deplace " . print_r($coord, 1) . "<br/>";
                    FlashBuilder::create( $this->legolas->getName() . " se deplace " . print_r($coord, 1) ,"success");

                    Redirect::getInstance()->setAct("fiche")->send();
                }
            }

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
    }

}