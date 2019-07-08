<?php


namespace App\Model\Entity\Game;


use App\Model\Entity\Game\Item\ItemEntity;

Trait CibleItemTrait
{
    /** @var
    public $id ; */

    /** @var int */
    public $value;

    /** @var ItemEntity  */
    public $cible ;

    /**
     * @param Game\Item\ $cible
     * @return CibleItemTrait
     */
    public function setCible(Item $item){

        $this->cible = $item ;

    }

    /**
     * @return ItemEntity
     */
    public function getCible(): ItemEntity
    {
        return $this->cible;
    }

    /**
     * @param int $value
     * @return CibleItemTrait
     */
    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

}