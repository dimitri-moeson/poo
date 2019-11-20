<?php


namespace App\Model\Heritage\Game\Item;


use App\Model\Comportement\CibleItemTrait;
use App\Model\Comportement\CiblePersonnageTrait;

use App\Model\Entity\Game\Item\ItemEntity;

use App\Model\Entity\GameComportement\CraftTrait;

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