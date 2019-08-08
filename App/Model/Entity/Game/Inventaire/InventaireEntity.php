<?php
namespace App\Model\Entity\Game\Inventaire;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Journal;
use ArrayAccess;
use Core\Model\Entity\Entity;
use Countable;

/**
 * Class InventaireEntity
 * @package App\InventaireEntity
 */
class InventaireEntity extends Entity implements ArrayAccess, Countable  {

    protected $container = array();

    public function __construct() {}

    public function __toString()
    {
        return $this->container;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {

        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return $this->container[$offset] ?? null;
    }

    public function count() {
        return count($this->container);
    }

    public function disponible(ItemEntity $item){

        return $this->offsetExists($item->inventaire_id);
    }

    /**
     * @param ItemEntity $item
     */
    public function retire(ItemEntity $item, $offset = null ){

        if($this->disponible($item))
        {
            if($this->offsetGet($item->inventaire_id)->val > 1)
            {
                $this->offsetGet($item->inventaire_id)->val--;
            }
            else
            {
                $this->offsetUnset($item->inventaire_id);
            }
        }
    }

    /**
     * @param ItemEntity $item
     * @return $this
     */
    public function ajoute(ItemEntity $item, $offset = null ){

        if($this->disponible($item))
        {
            $this->offsetGet($item->inventaire_id)->val++;
        }
        elseif(!is_null($offset))
        {
            if(!isset($item->val))$item->val = 1 ;
            $this->offsetSet($offset,$item) ;
        }
        else
        {
            if(!isset($item->val))$item->val = 1 ;
            $this->offsetSet($item->inventaire_id,$item) ;
        }


        return $this ;
    }

    /**
     * @return Item|null
     */
    public function getRand(): ?Item{

        if(!empty($this->container))
            return array_rand($this->container,1);

        return null;
    }

    /**
     * @return array
     */
    public function getContainer(): array
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getRubrique(): string
    {
        return $this->rubrique;
    }
}