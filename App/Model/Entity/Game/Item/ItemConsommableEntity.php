<?php


namespace App\Model\Entity\Game\Item;


use App\Model\Entity\Game\CibleItemTrait;
use App\Model\Entity\Game\CiblePersonnageTrait;
use App\Model\Entity\Game\CraftTrait;

class ItemConsommableEntity extends ItemEntity
{
    use CibleItemTrait;
    use CiblePersonnageTrait;
    use CraftTrait ;

    public static function init($nom, $type, $stat = null,$value = null){

        $item = new self();

        $item->setName($nom);
        $item->setType($type);

        //$item->setStat($stat);
        //$item->setValue($value);

        return $item ;
    }
}