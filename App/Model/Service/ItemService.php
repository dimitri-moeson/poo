<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 21:06
 */
namespace App\Model\Service ;

use App\Model\Entity\Game\Inventaire\InventaireEntity;
use App\Model\Entity\Game\Inventaire\InventaireEquipementEntity;
use App\Model\Entity\Game\Inventaire\InventaireRecetteEntity;
use App\Model\Entity\Game\Item\ItemConsommableEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Item\ItemMonstreEntity;
use App\Model\Entity\Game\Item\ItemQuestEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Item\ItemTable;
use Core\Debugger\Debugger;
use Exception;

class ItemService extends Service
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
        catch(Exception $e) {

            throw $e ;

        }
    }

    public function getRepo(ItemEntity $item){

        $type = $item->getType();

        $emplacements = ItemEntity::type_arr["equipement"]; //InventaireEquipementEntity::getPlaces();

        if(in_array($type,$emplacements)){

            return "Equipement";

        }

        return null ;

    }

    /**
     * @param $id
     * @return ItemEntity
     */
    public function getCraftable($id,$repo = null){

        //echo "id :$id<br/>";

        if($this->ItemBase instanceof ItemTable)
            $potion = $this->ItemBase->find($id); // $repo

        //var_dump($potion);

        if($potion instanceof ItemEntity)
            $potion = $this->getRecette($potion);
        else
            var_dump($potion);

        return $potion ;
    }

    /**
     * @param Int|null $personnage_id
     * @return mixed
     */
    public function listCraftable( Int $personnage_id = null, Int $batiment_id = null ){

        if(!is_null($personnage_id)){

            if($this->InventaireBase instanceof InventaireTable )
                return $this->InventaireBase->itemListing($personnage_id , "personnage" , "recette", null, ItemEntity::class );
        }

        $craftable_types = array("consommable","casque"  ,
            "epaule"  ,
            "amulette" ,
            "brassard"  ,
            "botte",
            "torse" ,
            "bague" ,
            "ceinture" ,
            "arme"  ,
            "bouclier" ,
            "jambe" ,
            "gant");

        return $this->ItemBase->typeListing($craftable_types);
    }

    /**
     * @param $id
     * @return ItemEntity
     */
    public function getQuest($id){

        $quest = $this->ItemBase->get($id);
        $quest = $this->getRecette($quest,"quest");

        return $quest ;


    }

    /**
     * @param Int|null $personnage_id
     * @return mixed
     */
    public function listQuest( Int $personnage_id = null ){

        if(!is_null($personnage_id)){

            return $this->InventaireBase->itemListing($personnage_id , "personnage" , "quest" );
        }

        return $this->ItemBase->typeListing(array("quest"));
    }

    public function getFightable(Int $strum_id = null){

        if($this->ItemBase instanceof ItemTable)
            $strum = $this->ItemBase->get($strum_id,ItemMonstreEntity::class);

        return $strum ;

    }

    /**
     * @param ItemEntity $potion
     * @param string $type
     * @return ItemEntity
     */
    public function getRecette(ItemEntity $potion, $type = "recette"){


        if($this->InventaireBase instanceof InventaireTable )
            $composition = $this->InventaireBase->itemListing($potion->id,null,$type,null,ItemEntity::class);

        $composants = new InventaireRecetteEntity();

            foreach ($composition as $composant ) {

                $composants[] = $composant;

            }
            //Debugger::getInstance()->add($composants);

            if($potion instanceof ItemEntity) {

               $potion->setRecette($composants);
            }

        return $potion ;
    }

    function getAttrib(ItemEntity $item){

        if( $item->isEquipable()) {

            //echo "equipable($item->inventaire_id)...";

            if($this->InventaireBase instanceof InventaireTable)
            {
                $res = $this->InventaireBase->itemListing($item->inventaire_id, "objet", null, array(

                    self::TYPE_RES,
                    self::TYPE_STAT

                ) ,ItemEntity::class);

                return $res;
            }

        }

        return array();
    }

    /**
     * generate Random Charac
     * monstre / equipement
     *
     * @param ItemEntity $item
     * @return ItemEntity
     * @throws Exception
     */
    function generateRandomCharac(ItemEntity $item){

        if( $item->isFightable()){

            if($this->ItemBase instanceof ItemTable)
                $res = $this->ItemBase->typeListing(array( self::TYPE_RES , self::TYPE_STAT ));

            foreach ($res as $composant )
            {
                if($composant instanceof ItemEntity)
                {
                    $composant->val = random_int( $composant->vie, 100);
                    $composant->inventaire_id = null ;
                    $composant->inventaire_rubrique = "objet";
                    $composant->inventaire_type = $composant->getType();
                    $composant->caract ="none";
                    $composant->record = true ;

                    if( $item instanceof ItemMonstreEntity)
                    {
                        if($composant->getType()== self::TYPE_RES )$item->getRessources()->ajoute($composant);
                        if($composant->getType()== self::TYPE_STAT )$item->getStats()->ajoute($composant);
                    }
                    /**if($this->InventaireService instanceof InventaireService)
                        $this->InventaireService->recordItemInInventaire($item->inventaire_id,$composant);*/
                }
            }

        }

        if( $item->isEquipable()){

            if($this->ItemBase instanceof ItemTable)
                $res = $this->ItemBase->typeListing(array( self::TYPE_RES , self::TYPE_STAT ));

            foreach ($res as $composant )
            {
                if($composant instanceof ItemEntity) {

                    $composant->val = random_int( $composant->vie, 100);
                    $composant->inventaire_id = null ;
                    $composant->inventaire_rubrique = "objet";
                    $composant->inventaire_type = $composant->getType();
                    $composant->caract ="none";
                    $composant->record = true ;

                    if($this->InventaireService instanceof InventaireService)
                        $this->InventaireService->recordItemInInventaire($item->inventaire_id,$composant);
                }
            }
        }

        return $item ;
    }
}