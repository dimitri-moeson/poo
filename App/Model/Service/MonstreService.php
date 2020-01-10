<?php

namespace App\Model\Service;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Heritage\Game\Item\ItemMonstreEntity;
use App\Model\Service;
use App\Model\Table\Game\Item\ItemTable;
use Core\Render\Render;
use Exception;

class MonstreService extends Service
{
    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        try {

            parent::__construct();

            $this->loadModel("Game\Item\Item");
            $this->loadService("Item");
        }catch(Exception $e){

            throw $e ;

        }
    }

    public function getFightable(Int $strum_id = null)
    {
        if($this->ItemBase instanceof ItemTable) {

            if(!is_null($strum_id))
                $strum = $this->ItemBase->get($strum_id, ItemMonstreEntity::class);
            else
                $strum = $this->ItemBase->randomOne("strum");
        }

        if($this->ItemService instanceof ItemService )
            $strum = $this->ItemService->generateRandomCharac($strum);

        return $strum ;
    }

    public function status( ItemEntity $personnage = null): ?String
    {
        return Render::getInstance()->block("perso.status",array(

            "personnage" => $personnage ,
        ));
    }
}