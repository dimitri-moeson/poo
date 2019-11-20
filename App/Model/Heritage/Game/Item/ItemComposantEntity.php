<?php


namespace App\Model\Heritage\Game\Item;


use App\Model\Entity\Game\Item\ItemEntity;

class ItemComposantEntity extends ItemEntity
{
    public static function init($nom, $type,$void = null, $void2 = null)
    {
        $item = new self();
        $item->setName($nom);
        $item->setType($type);
        return $item ;
    }
}