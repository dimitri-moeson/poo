<?php


namespace App\Model\Entity\Game\Item;


use App\Model\Entity\Game\CombattantTrait;
use App\Model\Entity\Game\DataTrait;

class ItemMonstreEntity extends ItemEntity
{
    use DataTrait, CombattantTrait;

    private $lootable = array();

    public function __construct()
    {
        parent::__construct();

        $this->initCombattant();
    }
}