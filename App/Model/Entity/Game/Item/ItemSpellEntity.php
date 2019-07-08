<?php


namespace App\Model\Entity\Game\Item;

use App\Model\Entity\Game\CibleItemTrait;

class ItemSpellEntity extends ItemEntity
{
    use CibleItemTrait;

    /** @var  */
    public $consommation ;

    public static function init($nom, $type, $consommation = null , $value = null ){

        $item = new self();

        $item->setName($nom);
        $item->setType($type);

        $item->setConsommation($consommation);
        $item->setValue($value);
        return $item ;
    }

    /**
     * @param mixed $consommation
     * @return ItemEntitySpell
     */
    public function setConsommation($consommation)
    {
        $this->consommation = $consommation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsommation()
    {
        return $this->consommation;
    }
}