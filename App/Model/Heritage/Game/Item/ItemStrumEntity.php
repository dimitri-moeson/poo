<?php


namespace App\Model\Heritage\Game\Item;


use App\Model\Comportement\CombattantTrait;
use App\Model\Comportement\DataTrait;
use App\Model\Entity\Game\Item\ItemEntity;

class ItemStrumEntity extends ItemEntity
{
    use DataTrait, CombattantTrait;

    private $lootable = array();

    public function __construct()
    {
        parent::__construct();

        $this->initCombattant();
    }
}