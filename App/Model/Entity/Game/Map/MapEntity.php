<?php
namespace App\Model\Entity\Game\Map ;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Heritage\Game\Item\ItemEntityTerrain;
use App\Model\Heritage\Game\Item\ItemTerrainEntity;

/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 09:50
 */

class MapEntity
{
    public $x ;

    public $y ;

    public $terrain ;

    public static function init($x , $y, $terrain = null )
    {
        $map = new self();
        $map->x = $x ;
        $map->y = $y ;

        $map->terrain = ItemTerrainEntity::init($terrain , "terrain");

        return $map ;

    }
}