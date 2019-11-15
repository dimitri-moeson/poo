<?php
namespace App\Model\Entity\Game\Item;

use App\Model\Entity\Game\DataTrait;
use App\Model\Entity\Game\Inventaire\InventaireRecetteEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use Core\Model\Entity\Entity;

/**
 * Class ItemEntity
 * @package App\ItemEntity
 * test edit
 */
class ItemEntity extends Entity
{
    use DataTrait;

    private $recette ;

    const type_arr = array(

        "politique" => array(

            "faction",
            "philosophie",
            "race"
        ),

        "classe" => array(

            "cac",
            "distant",
            "caster",
        ),

        "arme_1_main" => array(

            "épée",
            "hache",
            "javelot",
            "bouclier",
            "pistolet",
        ),

        "arme_2_main" => array(
            "fusil",
            "arc",
            "lance",
            "hallebarde",
            "espadon",
        ),

        "aventure" => array(

            "quest",
            "journaliere",
            "histoire",
            "epreuve",
        ),

        "batiment" => array(

            "forge",
            "ecole",
            "mairie",
            "magasin",
            "temple",
            "arene",

        ),

        "equipement" => array (

            "casque",
            "torse",
            "botte",
            "gant",
            "ceinture",
            "brassard",
            "amulette",
            "epaule",
            "jambe"

        ),

        "faune" => array(

            "strum",
            "pnj"
        ),

        "geographie" => array(

            "terrain",
        ),

        "item" => array(

            "consommable" ,
            "composant" ,

        ),

        "personnage" => array(

            "source",
            "statistique",
            "caractere"
        ),

        "competence" => array(

            "attaque",
            "defense",
            "soin",
            "renforcement",
            "débilitation"
        )
    );

    const categorie_arr = array(

        "status" => array(

            "normal",
            "mort"
        ),

        "mission" => array(

            "recolte",
            "collecte",
            "kill",
            "escorte",
            "livraison"

        ),

        "consommable" => array(

            "soin"  ,
            "nourriture"  ,
            "doppant"

        ),

        "ennemis" => array(

            "normal",
            "fort",
            "elite",
            "champion",
            "boss"
        ),

        "equipement" => array(

            "normal",
            "ancien",
            "primordial",
            "légendaire"
        ),

        "statistique" => array(

            "offensif" ,
            "defensif"

        ),

        "personnage" => array(

            "bonus",
            "malus"
        ),

        "role" => array(

            "tank",
            "degat",
            "healer",
            "support",
            "sapeur"
        ),

        "action" => array(

            "requis",
            "ajout",
            "cout",
        )
    );

    /**
     * ItemEntity constructor.
     * @param $nom
     * @param $type
     */
    public function __construct()
    {
        if($this->isCraftable())
            $this->recette = new InventaireRecetteEntity();
    }

    /**
     * @param ItemEntity $item
     * @return ItemEntity
     */
    public static function create(ItemEntity $item ):self
    {
        $classe_name = __NAMESPACE__."\Item".ucfirst(strtolower($item->getType()))."Entity";

        $perso = new $classe_name();

        if($perso instanceof ItemEntity){
            $perso->setType($item->getType());
            $perso->setName($item->getName());
            $perso->setDescription($item->getDescription());
            $perso->setImg($item->getImg());
        }

        return $perso ;

    }

    /**
     * @return mixed
     */
    public function getVal()
    {
        if(isset($this->val))
            return $this->val;
    }

    /**
     * @return mixed
     */
    public function getStat()
    {
        if(isset($this->stat))
            return $this->stat;
    }

    /**
     * @return bool
     */
    public function isEquipable(){

        $equipable = self::getEquipableTypeArray();

        if(in_array($this->getType(),$equipable)){

            return true ;
        }
        return false ;

        if(in_array($this->getType(),self::type_arr["equipement"])){

            return true ;
        }
        if(in_array($this->getType(),self::type_arr["arme_1_main"])){

            return true ;
        }
        if(in_array($this->getType(),self::type_arr["arme_2_main"])){

            return true ;
        }
        return false ;
    }

    /**
     * @return bool
     */
    public function isFightable(){

        if($this->getType()==="strum")
        {
            return true;
        }

        return false ;
    }

    /**
     * @return array
     */
    public static function getEquipableTypeArray()
    {
        return array_merge(

            self::type_arr['equipement'] ,
            self::type_arr['arme_1_main'] ,
            self::type_arr['arme_2_main']

        );
    }

    /**
     * @return array
     */
    public static function getCraftableTypeArray()
    {
        return array_merge(

            self::type_arr['equipement'] ,
            self::type_arr['arme_1_main'] ,
            self::type_arr['arme_2_main'] ,
            array('consommable')

        );
    }

    public static function getClasseArray(){

        return array_merge(

            ItemEntity::type_arr['classe']
        );
    }

    /**
     * @return array
     */
    public static function getPlaceTypeArray(){

       return array_merge(

           ItemEntity::type_arr['batiment'] ,
           ItemEntity::type_arr['aventure']
       );
    }

    /**
     * @return bool
     */
    public function isCraftable(){

        $craftable_types = self::getCraftableTypeArray();

        /**array_merge(

            self::type_arr['equipement'] ,
            self::type_arr['arme_1_main'] ,
            self::type_arr['arme_2_main'] ,
            array('consommable')

        );**/

        if(in_array($this->getType(),$craftable_types)){

            return true ;
        }

        return false ;
    }

    /**
     * @return InventaireRecetteEntity
     */
    public function getRecette(){

        if($this->isCraftable())
            return $this->recette ;
    }

    /**
     * @param mixed $recette
     */
    public function setRecette(InventaireRecetteEntity $recette)
    {
        if($this->isCraftable())
            $this->recette = $recette;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxVal()
    {
        return $this->getVal();
    }
}