<?php


namespace App\Controller\Game;


use App\Model\Heritage\Game\Item\ItemMonstreEntity;
use App\Model\Service\ItemService;
use App\Model\Service\PersonnageService;
use App\Model\Table\Game\Item\ItemTable;
use Core\HTML\Env\Post;
use Core\Render\Render;

class PlaceController extends AppController
{
    /**
     * SkillController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        Render::getInstance()->setTemplate("place");
    }

    /**
     * @param $id donneur de quest
     */
    public function quest($id)
    {
        if (Post::getInstance()->has("accept")) {
            $quest2 = $this->ItemService->getQuest(Post::getInstance()->val('quest'));
            if($this->PersonnageService instanceof PersonnageService )
                $this->PersonnageService->accepte($this->legolas, $quest2);
        }

        $this->questable = $this->ItemService->listQuest();
    }

    /**
     * @param $id batiment arene
     */
    public function arena($id){

        /**if ( Post::getInstance()->has('defi') && Post::getInstance()->has('challenger'))// debut engagement
        {

        }**/

        if($this->Item instanceof ItemTable ) {
            $this->sacoche = $this->Item->typeListing(array("strum"), ItemMonstreEntity::class);
            $_SESSION["position"]["batiment"]["id"] = $id;

            Render::getInstance()->setView("Game/Arena/Roaster");
        }
    }

    /**
     * @param $id du batiment
     */
    public function craft($id)
    {
        if ($this->ItemService instanceof ItemService) {

            $this->craftables = $this->ItemService->listCraftable($this->legolas->id);
            $_SESSION["position"]["batiment"]["id"] = $id;

            Render::getInstance()->setView("Game/Place/Craft");
        }

    }

    /**
     * @param $id du batiment
     */
    public function apprentissage($id)
    {
        if($this->ItemService instanceof ItemService) {

            $this->craftables = $this->ItemService->listCraftable(null ,$id);
            $_SESSION["position"]["batiment"]["id"] = $id;

            Render::getInstance()->setView("Game/Place/Apprentissage");
        }
    }
}