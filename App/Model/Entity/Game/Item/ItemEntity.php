<?php
namespace App\Model\Entity\Game\Item;

use App\Model\Comportement\DataTrait;
use App\Model\Comportement\ItemTrait;
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
    use ItemTrait;
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

}