<?php


namespace App\Model\Service;

use App\Model\Heritage\Game\Item\ItemEquipementEntity;
use App\Model\Heritage\Game\Inventaire\InventaireEquipementEntity;

use App\Model\Object\Journal;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Item\ItemTable;
use Core\Render\Render;

/**
 * Class EquipementService
 * @package App\Model\Service
 */
class EquipementService extends Service
{
    /**
     * public $emplacements = array(
     *
     * "casque","torse","botte","gant"
     *
     * );
     **/
    private static $type_sac = "sac";
    private static $type_equip = "equipement";

    private static $rub_perso = "personnage";
    private static $rub_equip = "equipement";


    /** @var PersonnageEntity */
    private $personnage;

    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Personnage\Personnage");
        $this->loadModel("Game\Inventaire\Inventaire");
        $this->loadModel("Game\Item\Item");

        $this->loadService("Personnage");

    }

    /**
     * @param PersonnageEntity $personnage
     * @return string
     */
    public function equiped(PersonnageEntity $pe = null)
    {
        $perso = is_null($pe) ? $this->personnage : $pe ;

        $armements = $perso->getArmement()->getContainer();
        $equipements = $perso->getEquipement()->getContainer();

        return Render::getInstance()->block("equipement",array(

            "sexe" => $perso->sexe == 1 ? "homme" : ($perso->sexe == 2 ? "femme" : "scion"),
            "equipements" => $equipements ,
            "armements" => $armements
        ));
    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     */
    function equip(ItemEquipementEntity $item)
    {
        $piece = $this->personnage->getEquipement()->offsetGet($item->getType());

        if ($piece !== null) {
            //echo "EquipServ->retireFirst()<br/>";
            $this->retire($piece);
        }

        //echo "equip => extract<br/>";
        $this->personnage->retire($item);

        $item->parent_id = $this->personnage->id;
        $item->inventaire_type = self::$type_equip ;
        $item->inventaire_rubrique = self::$rub_perso;
        $item->val = 1 ;
        $item->record = true;

        //echo "<br/>perso->equip => go<br/>";
        //var_dump($item); echo "<br/>";
        $this->personnage->equip($item);

        //var_dump($personnage->getEquipement()->getContainer()); echo "<br/>";
        Journal::getInstance()->add($this->personnage->getName() . " equipe " . $item->getName() . "<br/>");

        if($this->PersonnageService instanceof PersonnageService)
            $this->PersonnageService->save($this->personnage);

    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     */
    function retire(ItemEquipementEntity $item)
    {

        //echo "equipServ->retir(".$item->getType().")<br/>";
        $this->personnage->desequip($item->getType());

        $item->parent_id = $this->personnage->id;
        $item->inventaire_type = self::$type_sac;
        $item->inventaire_rubrique = self::$rub_perso;
        $item->val = 1 ;
        $item->record = true;

        //var_dump($item);

       // echo "<br/>retire => rangement<br/>";
        $this->personnage->ajoute($item);

        //Journal::getInstance()->add($this->personnage->getName() . " retire " . $item->getName() . "<br/>");

        if($this->PersonnageService instanceof PersonnageService)
            $this->PersonnageService->save($this->personnage);

    }

    /**
     * @param PersonnageEntity $personnage
     * @param $emplacement
     * @return bool
     */
    function already_equiped($emplacement)
    {

        $piece = $this->personnage->getEquipement()->offsetGet($emplacement);

        return ($piece !== null);

    }

    /**
     * @param $id
     * @return ItemEntity
     */
    public function getRangeable($id)
    {
        //echo "EquipServ->getRangeable($id)<br/>";
        if ($this->ItemBase instanceof ItemTable) {

            $potion = $this->ItemBase->getInInventaire($id,ItemEquipementEntity::class );

            if($potion instanceof ItemEquipementEntity)
            {
                //echo "EquipServ->getRangeable($id) : ".$potion->getType()."<br/>";
                if (in_array($potion->getType(), InventaireEquipementEntity::getPlaces()))
                {
                    return $potion;
                }
            }
        }
    }

    /**
     * @param $id
     * @return ItemEntity
     */
    public function getEquipable($id)
    {
        if ($this->ItemBase instanceof ItemTable) {

            $potion = $this->ItemBase->getInInventaire($id, ItemEquipementEntity::class );

            if($potion instanceof ItemEquipementEntity)
            {
                if( in_array($potion->getType(), InventaireEquipementEntity::getPlaces()))
                {
                    return $potion;
                }
            }
        }
    }

    /**
     * @param Int|null $personnage_id
     * @param null $place
     * @return mixed
     */
    public function listEquipable($place = null)
    {
        $emplacements = InventaireEquipementEntity::getPlaces();

        if($this->InventaireBase instanceof InventaireTable) {
            if (!is_null($place)) {

                return $this->InventaireBase->itemListing($this->personnage->id, "personnage", "sac", array($place), ItemEquipementEntity::class);
            }

            return $this->InventaireBase->itemListing($this->personnage->id, "personnage", "sac", $emplacements, ItemEquipementEntity::class);
        }
    }

    /**
     * @param PersonnageEntity $personnage
     */
    public function setPersonnage(PersonnageEntity $personnage): void
    {
        $this->personnage = $personnage;
    }

}