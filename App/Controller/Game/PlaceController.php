<?php


namespace App\Controller\Game;


use App\Model\Entity\Journal;
use App\Model\Service\ItemService;
use App\Model\Service\PersonnageService;
use App\Model\Service\QuestService;
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
            
            $this->craftables = $this->ItemService->listCraftable();
            $_SESSION["position"]["batiment"]["id"] = $id;

            Render::getInstance()->setView("Game/Place/Apprentissage");
        }
    }
}