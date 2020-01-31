<?php


namespace App\Controller\Game;


use App;
use App\Model\Object\Combat\Defi;
use App\Model\Heritage\Game\Item\ItemConsommableEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service\CombatService;
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
        $this->loadService("Monstre");

        if (isset($_SESSION['defi'])) {
            $this->defi = unserialize($_SESSION['defi']);
        }
    }

    /**
     * @return bool
     */
    private function verif_valid_fight()
    {
        if (isset($this->defi) && !empty($this->defi) && !is_null($this->defi))
        {
            if ($this->defi instanceof Defi)
            {
                if ($this->CombatService instanceof CombatService)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     */
    public function index()
    {
        if ($this->verif_valid_fight()) {

            echo "test...";

            Render::getInstance()->setView("Game/Fight/Combat");
        }
    }


    /**
     * fin du combat
     */
    public function bilan()
    {
        if ($this->verif_valid_fight()) {
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

    /**
     * le joueur a passé son tour
     */
    function next()
    {
        if ($this->verif_valid_fight())
        {
            $this->defi = $this->CombatService->deroule($this->defi);
            $_SESSION['defi'] = serialize($this->defi);
            Redirect::getInstance()->setAct("index")->setCtl("fight")->setDom("game")->send();
        }
    }

    /**
     * le joeur passe son tour
     * le combat suit son cours
     */
    public function deroule()
    {
        if ($this->verif_valid_fight())
        {

            if (Post::getInstance()->has('action')) {
                $action = Post::getInstance()->val('action');

                if ($action == 'combat') {
                    $this->defi = $this->CombatService->deroule($this->defi);
                }
                $_SESSION['defi'] = serialize($this->defi);
                Redirect::getInstance()->setAct("index")->setCtl("fight")->setDom("game")->send();

            }

        }
    }

    /**
     * le joueur fuit l'affrontement...
     */
    public function fuite()
    {
        if ($this->verif_valid_fight()) {

            if (Post::getInstance()->has('action')) {
                $action = Post::getInstance()->val('action');

                if ($action == 'fuite') {
                    FlashBuilder::create($this->legolas->getName() . "a fui combat", "success");
                    Redirect::getInstance()->setAct("index")->setCtl("default")->setDom("game")->send();
                }

                Redirect::getInstance()->setAct("index")->setCtl("fight")->setDom("game")->send();

            }

        }
    }

    /**
     * le joueur lance une attaque de base sur une cible precise
     *
     *
     */
    public function attaque()
    {
        if ($this->verif_valid_fight())
        {
            if (Post::getInstance()->has('action')) {
                $action = Post::getInstance()->val('action');

                if ($action == 'attaque') {
                    $cible = $this->defi->offsetGet(Post::getInstance()->val('rank'));
                    $this->defi = $this->CombatService->ciblage($this->defi, $cible);
                    $_SESSION['defi'] = serialize($this->defi);
                }
                Redirect::getInstance()->setAct("index")->setCtl("fight")->setDom("game")->send();
            }

        }
    }

    /**
     *
     */
    public function default()
    {
        if ($this->verif_valid_fight()) {
            if (Post::getInstance()->has('action')) {

                $action = Post::getInstance()->val('action');

                /**if ( $action == 'attaque')
                 * {
                 * $cible = $this->defi->offsetGet(Post::getInstance()->val('rank'));
                 * $this->CombatService->ciblage($this->defi, $cible);
                 * }**/
                // else
                /**if ( $action == 'combat')
                 * {
                 * $this->CombatService->deroule($this->defi);
                 * }**/
                //else
                /**if ( $action == 'fuite')
                 * {
                 * FlashBuilder::create( $this->legolas->getName() . "a fui combat" ,"success");
                 * Redirect::getInstance()->setAct("default")->setCtl("game");
                 * }**/

            } else if (Post::getInstance()->has('bilan')) {

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
}