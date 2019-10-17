<?php


namespace App\Controller\Game;


use App;
use App\Model\Entity\Game\Combat\Defi;
use App\Model\Entity\Game\Item\ItemConsommableEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service\CombatService;
use App\Model\Service\EquipementService;
use App\Model\Service\PersonnageService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Post;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

/**
 * Class FightController
 * @package App\Controller\Game
 */
class FightController extends AppController
{
    /**
     * FightController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        Render::getInstance()->setTemplate("test.combat");

        $this->loadService("Combat");
        //$this->loadService("Combat");

        $this->loadService("Monstre");

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


    }

    /**
     *
     */
    public function index(){

        Render::getInstance()->setView("Game/Fight/Combat");

    }

    /**
     * le joueur lance une attaque de base.
     */
    public function attaque(){

        if ($this->defi instanceof Defi)
        {
            if ($this->CombatService instanceof CombatService)
            {
                $cible = $this->defi->offsetGet(Post::getInstance()->val('rank'));//$this->Personnage->find(Post::getInstance()->val('cible'));

                $this->CombatService->ciblage($this->defi, $cible);

                $_SESSION['defi'] = serialize($this->defi);
            }
        }
    }

    /**
     * le joueur fuit l'affrontement...
     */
    public function fuite(){

        FlashBuilder::create( $this->legolas->getName() . "a fui combat" ,"success");

        Redirect::getInstance()->setAct("fiche");
    }

    /**
     * fin du combat
     */
    public function bilan(){

        if ($this->defi instanceof Defi) {
            if ($this->CombatService instanceof CombatService) {
                $mess = null;

                $player = $this->defi->current();

                $mess .= $player->getName() . "est sorti vainqueur du combat";
                unset($_SESSION['defi']);

                $potion = ItemConsommableEntity::init("potion", "soin", "vie", 15);

                if ($player instanceof PersonnageEntity) {
                    if ($this->PersonnageService->ramasse($player, $potion)) {

                        $mess .= $player->getName() . " ramasse " . $potion->getName();
                        FlashBuilder::create($mess, "success");

                        Redirect::getInstance()->setAct("fiche")->send();
                    }
                }
            }
        }
    }

    /**
     * le joueur a passé son tour
     */
    function next(){

        if ($this->defi instanceof Defi)
            if($this->CombatService instanceof CombatService)
                $this->CombatService->deroule($this->defi);

    }

    /**
     *
     */
    public function default()
    {

        if ($this->defi instanceof Defi)
        {
            if($this->CombatService instanceof CombatService)
            {
                if(Post::getInstance()->has('action'))
                {
                    if (Post::getInstance()->val('action') == 'attaque')
                    {

                    }
                    elseif ( Post::getInstance()->val('action') == 'combat')
                    {
                    }
                    elseif ( Post::getInstance()->val('action') == 'fuite')
                    {

                    }
                }
                else if (Post::getInstance()->has('bilan')) {


                }
            }
        }

    }

}