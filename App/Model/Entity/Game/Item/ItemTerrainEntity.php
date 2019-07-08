<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 10:00
 */

namespace App\Model\Entity\Game\Item;


class ItemTerrainEntity extends ItemEntity
{

    public static function init($nom, $type ){

        $item = new self($nom, $type);

        return $item ;
    }
}