<?php

namespace App\Controller;

use App;
use App\Model\Entity\Game\Item\ItemConsommableEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Item\ItemEquipementEntity;
use App\Model\Entity\Game\Item\ItemMonstreEntity;
use App\Model\Entity\Journal;

use App\Model\Entity\Game\Combat\Defi;
use App\Model\Entity\Game\Personnage\Archer;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Game\Personnage\Voleur;

use App\Model\Service\CombatService;
use App\Model\Service\MonstreService;
use App\Model\Service\ItemService;
use App\Model\Service\MapService;
use App\Model\Service\MovementService;
use App\Model\Service\PersonnageService;

use App\Model\Service\QuestService;
use App\Model\Service\UserService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Item\ItemTable;

use Core\Auth\DatabaseAuth;
use Core\Debugger\Debugger;

use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;

use App\Model\Service\EquipementService;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Request\Request;
use Core\Session\FlashBuilder;

/**
 * Class TestController
 * @package App\Controller
 */
class TestController extends AppController
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

    /**
     *
     */
    public function fiche()
    {
        Debugger::getInstance()->perso($this->legolas);

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

        if($this->Inventaire instanceof InventaireTable ) {

            $e_types = Get::getInstance()->has('place')  ? array(Get::getInstance()->val('place')) : null ;
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id, "personnage", "sac", $e_types , ItemEntity::class);
        }

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
    public function recolte()
    {
        if (Post::getInstance()->has('recolte')) {
            $composants = $this->Item->allOf("composant");
            $item_rand = rand(1, count($composants));
            $item = $composants[($item_rand - 1)];

            if ($this->PersonnageService->ramasse($this->legolas, $item)) {

                $message = $this->legolas->getName() . " ramasse " . $item->getName();

                if ($this->QuestService instanceof QuestService) {
                    if($this->QuestService->verifProgress($this->legolas, $item));
                }

                FlashBuilder::create( "$message","success");

                Redirect::getInstance()->setAct("fiche")->send();

            }
        }
    }

    /**
     *
     */
    public function jeter()
    {
        if (Get::getInstance()->has('id'))
        {
            if($this->Inventaire instanceof InventaireTable )
            {
                $_id = Get::getInstance()->val('id');

                $inv = $this->Inventaire->find($_id);

                if($inv) {

                    if ($this->Item instanceof ItemTable) {

                        $itm = $this->Item->find($inv->child_id);

                    }

                    $this->Inventaire->archive($_id);

                    if($itm) {
                        Redirect::getInstance()->setParams(array("place" => $itm->type))
                            ->setAct("fiche")
                            ->send();
                    }
                }
            }
        }

        Redirect::getInstance()->setAct("fiche")->send();
    }

    /**
     *
     */
    public function apprentissage()
    {
        if (Post::getInstance()->has('enseignement')) {
            if ($this->legolas instanceof PersonnageEntity) {
                $repo = Post::getInstance()->val('repo') ?? null;

                if($this->ItemService instanceof ItemService)
                    $potion = $this->ItemService->getCraftable(Post::getInstance()->val('craft'), $repo);

                $this->PersonnageService->apprendre($this->legolas, $potion);

                if ($this->QuestService instanceof QuestService) {
                    $this->QuestService->verifProgress($this->legolas, $potion);
                }
                //$this->viewText = $this->legolas->getName() . " apprend la recette " . $potion->getName() . "<br/>";
                FlashBuilder::create( $this->legolas->getName() . " apprend la recette " . $potion->getName(),"success");

                Redirect::getInstance()->setAct("fiche")->send();
            }
        }
        if($this->ItemService instanceof ItemService)
            $this->craftables = $this->ItemService->listCraftable();
    }

    /**
     *
     */
    public function craft()
    {
        if($this->ItemService instanceof ItemService)
        {
            if (Post::getInstance()->has('fabrique')) {

                Journal::getInstance()->add($this->legolas->getName() . " tente un craft<br/>");
                $repo = Post::getInstance()->val('repo') ?? null;
                $potion = $this->ItemService->getCraftable(Post::getInstance()->val('item'), $repo);

                if($this->PersonnageService instanceof PersonnageService)
                    $craft = $this->PersonnageService->craft($this->legolas, $potion);

                if ($this->QuestService instanceof QuestService) {
                    $this->QuestService->verifProgress($this->legolas, $potion);
                }
                    if ($craft === true)    $viewText = $this->legolas->getName() . " fabrique " . $potion->getName() . "<br/>";
                elseif ($craft === 2)       $viewText = $this->legolas->getName() . " n'a pas tous les ingrédients à sa disposition pour fabriquer " . $potion->getName() . ".<br/>";
                elseif ($craft === 3)       $viewText = $this->legolas->getName() . " ne connait pas la recette " . $potion->getName();
                elseif ($craft === 4)       $viewText = " erreur inconnue ";

                FlashBuilder::create( "$viewText","success");

                Redirect::getInstance()->setAct("fiche")->send();
            }

            $this->craftables = $this->ItemService->listCraftable($this->legolas->id);
        }

        if($this->Inventaire instanceof InventaireTable )
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id , "personnage" , "sac", array("composant"), ItemEntity::class );

    }

    /**
     *
     */
    public function arene(){

        if($this->EquipementService instanceof EquipementService) {
            $this->EquipementService->setPersonnage($this->legolas);
        }

        if ( Post::getInstance()->has('defi') && Post::getInstance()->has('challenger'))// debut engagement
        {
            if($this->MonstreService instanceof MonstreService ) {
                $strum = $this->MonstreService->getFightable(Post::getInstance()->val("challenger"));

                if($strum instanceof ItemMonstreEntity) {

                    $strum->setPosition($this->legolas->getPosition());

                    $defi = new Defi(array($this->legolas, $strum));

                    $_SESSION['defi'] = serialize($defi);
                    FlashBuilder::create( "combat lancé","success");

                    Redirect::getInstance()->setAct('combat')->send();
                }
            }
        }

        if($this->Item instanceof ItemTable ) {
            $this->sacoche = $this->Item->typeListing(array("strum"), ItemMonstreEntity::class);
        }
    }

    /**
     *
     */
    public function combat()
    {
        Render::getInstance()->setTemplate("test.combat");

        if($this->EquipementService instanceof EquipementService) {
            $this->EquipementService->setPersonnage($this->legolas);
        }

        if($this->Inventaire instanceof InventaireTable )
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id , "personnage" , "sac", array("consommable"), ItemEntity::class );

        if (isset($_SESSION['defi']))
        {
            $this->defi = unserialize($_SESSION['defi']);
        }
        else // debut engagement
        {
            $merlin = $this->Personnage->find(2);
            $harry = $this->Personnage->find(3);

            $this->defi = new Defi(array($merlin, $harry, $this->legolas));
        }

        if ($this->defi instanceof Defi)
        {
            if($this->CombatService instanceof CombatService)
            {
                if(Post::getInstance()->has('action'))
                {
                    if (Post::getInstance()->val('action') == 'attaque')
                    {
                        $cible = $this->defi->offsetGet(Post::getInstance()->val('rank'));//$this->Personnage->find(Post::getInstance()->val('cible'));

                        $this->CombatService->ciblage($this->defi, $cible);
                    }
                    elseif ( Post::getInstance()->val('action') == 'combat')
                    {
                        $this->CombatService->deroule($this->defi);
                    }
                    elseif ( Post::getInstance()->val('action') == 'fuite')
                    {
                        FlashBuilder::create( $this->legolas->getName() . "a fui combat" ,"success");

                        Redirect::getInstance()->setAct("fiche");
                    }
                }
                else if (Post::getInstance()->has('bilan')) {

                    $mess = null ;

                    $player = $this->defi->current();

                    $mess .= $player->getName() . "est sorti vainqueur du combat";
                    unset($_SESSION['defi']);

                    $potion = ItemConsommableEntity::init("potion", "soin", "vie", 15);

                    if( $player instanceof PersonnageEntity) {
                        if ($this->PersonnageService->ramasse($player, $potion)) {

                            $mess .= $player->getName() . " ramasse " . $potion->getName();
                            FlashBuilder::create( $mess ,"success");

                            Redirect::getInstance()->setAct("fiche")->send();
                        }
                    }
                }
            }
        }

        $_SESSION['defi'] = serialize($this->defi);
    }

    /**
     *
     */
    public function quest()
    {
        if (Post::getInstance()->has("accept")) {
            $quest2 = $this->ItemService->getQuest(Post::getInstance()->val('quest'));
            if($this->PersonnageService instanceof PersonnageService )
                $this->PersonnageService->accepte($this->legolas, $quest2);
        }

        $this->questable = $this->ItemService->listQuest();
    }

    /**
     *
     */
    public function item(){

        $this->obj = $this->Inventaire->find(955);//itemListing($this->legolas->id , "personnage" ,"equipement", null, ItemEntity::class );
        $this->item = $this->Item->find($this->obj->child_id);

        $this->properties = $this->Inventaire->itemListing($this->obj->id , "objet" ,null, null, ItemEntity::class );
    }
}