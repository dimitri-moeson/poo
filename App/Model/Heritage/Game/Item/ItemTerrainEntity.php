<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 10:00
 */

namespace App\Model\Heritage\Game\Item;


use App\Model\Entity\Game\Item\ItemEntity;

class ItemTerrainEntity extends ItemEntity
{

    public static function init($nom, $type ){

        $item = new self($nom, $type);

        return $item ;
    }
}