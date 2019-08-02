<?php


namespace App\Model\Service;


use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Item\ItemQuestEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;

class QuestService extends Service
{
    /**
     * ItemService constructor.
     */
    public function __construct()
    {
        try {

            parent::__construct();

            $this->loadModel("Game\Inventaire\Inventaire");
            $this->loadModel("Game\Item\Item");

            $this->loadService("Inventaire");

        }
        catch(\Exception $e) {

            throw $e ;

        }
    }

    /**
     * @param $persoId
     * @param $item
     * @return bool
     */
    function verifProgress(PersonnageEntity $perso, ItemEntity $item){

        if( $this->InventaireBase instanceof InventaireTable ) {

            $sac = $this->InventaireBase->itemListing($perso->getId(), self::RUB_PERSO, self::TYPE_QUEST, null , ItemQuestEntity::class);

            foreach ($sac as $quest) {

                if($quest instanceof ItemQuestEntity){

                    if($quest->realise($item)){

                        if($quest->complete()){
                            return true ;
                        }
                    }

                }
            }
        }
    }
}