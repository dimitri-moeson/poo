<?php


namespace App\Controller\Game;


use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Journal;
use App\Model\Service\ItemService;
use App\Model\Service\PersonnageService;
use App\Model\Service\QuestService;
use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Item\ItemTable;
use Core\HTML\Env\Post;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class SkillController extends AppController
{
    /**
     * SkillController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function recolte()
    {
        //if (Post::getInstance()->has('recolte'))
        if ($this->PersonnageService instanceof PersonnageService) {
            if ($this->Item instanceof ItemTable)
                $item = $this->Item->randomOne("composant");

            /*$item_rand = rand(1, count($composants));
            $item = $composants[($item_rand - 1)];*/

            if ($this->PersonnageService->ramasse($this->legolas, $item)) {

                $message = $this->legolas->getName() . " ramasse " . $item->getName();

                if ($this->QuestService instanceof QuestService) {
                    if ($this->QuestService->verifProgress($this->legolas, $item)) ;
                }
            }
        }

        if ($message) FlashBuilder::create("$message", "success");

        Redirect::getInstance()
            ->setDom("game")->setCtl("default")->setAct("index")
            ->setParams(array("place" => "composant"));

        //echo "<br/>".Redirect::getInstance();
        //die;

        Redirect::getInstance()->send();
    }

    /**
     *
     */
    public function craft()
    {
        if($this->ItemService instanceof ItemService)
        {
            if (Post::getInstance()->has('craft')) {

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

            }

           // $this->craftables = $this->ItemService->listCraftable($this->legolas->id);
        }

        //if($this->Inventaire instanceof InventaireTable )
        //    $this->sacoche = $this->Inventaire->itemListing($this->legolas->id , "personnage" , "sac", array("composant"), ItemEntity::class );

        FlashBuilder::create( "$viewText","success");

        Redirect::getInstance()
            ->setDom("game")->setCtl("place")->setAct("craft")
            ->send();
    }

    /**
     *
     */
    public function apprentissage()
    {
        if (Post::getInstance()->has('apprentissage'))
        {
            if ($this->legolas instanceof PersonnageEntity)
            {
                $repo = Post::getInstance()->val('repo') ?? null;

                if($this->ItemService instanceof ItemService)
                    $potion = $this->ItemService->getCraftable(Post::getInstance()->val('craft'), $repo);

                $this->PersonnageService->apprendre($this->legolas, $potion);

                if ($this->QuestService instanceof QuestService)
                {
                    $this->QuestService->verifProgress($this->legolas, $potion);
                }

                FlashBuilder::create( $this->legolas->getName() . " apprend la recette " . $potion->getName(),"success");

                Redirect::getInstance()
                    ->setDom("game")->setCtl("place")->setAct("apprentissage")
                    ->send();
            }
        }
    }
}