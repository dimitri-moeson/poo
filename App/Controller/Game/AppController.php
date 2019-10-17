<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */
namespace App\Controller\Game;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Service\EquipementService;
use App\Model\Service\PersonnageService;
use App\Model\Service\UserService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\Auth\DatabaseAuth;
use Core\Redirect\Redirect;

class AppController extends \App\Controller\AppController
{
    protected $template = 'game';

    public function __construct()
    {
        parent::__construct();

        // Auth

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());

        $this->loadService("User");
        $this->loadService("Personnage");
        $this->loadService("Equipement");
        $this->loadService("Inventaire");

        $this->loadModel("Game\Personnage\Personnage");
        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Inventaire\Inventaire");

        if(!$this->auth->logged()){

            $this->forbidden();
        }

        $this->template = 'game';
        if ($this->PersonnageService instanceof PersonnageService) {
            $this->legolas = $this->PersonnageService->recup($this->auth->getUser('id'));
        }

        if (!$this->legolas){

            if ($this->UserService instanceof UserService) {
                $this->UserService->initPerso($this->auth->getUser('id'));
                Redirect::getInstance()->setCtl("inscription")->setAct("faction")->send();
            }

        }else {

            if($this->EquipementService instanceof EquipementService) {
                $this->EquipementService->setPersonnage($this->legolas);
            }

            if($this->Inventaire instanceof InventaireTable )
                $this->sacoche = $this->Inventaire->itemListing($this->legolas->id , "personnage" , "sac", array("consommable"), ItemEntity::class );
        }
    }
}