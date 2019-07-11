<?php

namespace App\Controller;

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

use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Item\ItemTable;

use Core\Debugger\Debugger;

use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;

use App\Model\Service\EquipementService;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Request\Request;

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


        if ($this->PersonnageService instanceof PersonnageService) {
            $this->legolas = $this->PersonnageService->recup(1);
        }

        if (!$this->legolas) $this->notFound("personnage");

        if($this->Inventaire instanceof InventaireTable )
            $this->sacoche = $this->Inventaire->itemListing($this->legolas->id , "personnage" , "sac", null, ItemEntity::class );
    }

    private function equipement(){


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

                if (Post::getInstance()->has('retire', 'post')) {
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

                $this->viewText = $this->legolas->getName() . " change d'equipement " . $equip->getName() . "<br/>";
                Redirect::reload();
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

                    $this->viewText = $this->legolas->getName() . " se deplace " . print_r($coord, 1) . "<br/>";
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

                $this->viewText = $this->legolas->getName() . " ramasse " . $item->getName() . "<br/>";
            }
        }
    }

    /**
     *
     */
    public function remove()
    {
        if (Get::getInstance()->has('id'))
        {
            if($this->Inventaire instanceof InventaireTable )
            {
                $_id = Get::getInstance()->val('id');

                $this->Inventaire->delete($_id);
            }
        }

        Redirect::redirect("fiche");
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

                $this->viewText = $this->legolas->getName() . " apprend la recette " . $potion->getName() . "<br/>";
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

                    if ($craft === true)    $this->viewText = $this->legolas->getName() . " fabrique " . $potion->getName() . "<br/>";
                elseif ($craft === 2)       $this->viewText = $this->name . " n'a pas tous les ingrédients à sa disposition.";
                elseif ($craft === 3)       $this->viewText = $this->name . " ne connait pas la recette " . $potion->getName();
                elseif ($craft === 4)       $this->viewText = " erreur inconnue ";
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

                    Redirect::redirect('combat');
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

                        var_dump($cible);

                        $this->CombatService->ciblage($this->defi, $cible);
                    }
                    elseif ( Post::getInstance()->val('action') == 'combat')
                    {
                        $this->CombatService->deroule($this->defi);
                    }
                    elseif ( Post::getInstance()->val('action') == 'fuite')
                    {
                        Journal::getInstance()->add($this->legolas->getName() . "a fui combat");
                        header('location:?p=test.fiche');
                    }
                }
                else if (Post::getInstance()->has('bilan')) {

                    Journal::getInstance()->add($this->defi->current()->getName() . "est sorti vainqueur du combat");

                    unset($_SESSION['defi']);

                    $potion = ItemConsommableEntity::init("potion", "soin", "vie", 15);

                    $this->defi->current()->ajoute($potion);
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