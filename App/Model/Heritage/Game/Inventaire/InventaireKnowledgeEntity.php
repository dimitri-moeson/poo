<?php


namespace App\Model\Heritage\Game\Inventaire;

use App\Model\Entity\Game\Inventaire\InventaireEntity;
use App\Model\Entity\Game\Item\ItemEntity;

/**
 * Class InventaireEntityKnowledge
 * @package App\InventaireEntity
 */
class InventaireKnowledgeEntity extends InventaireEntity
{
    public function disponible(ItemEntity $item){

        //echo "knows->exists($item->id)<br/>";
        return $this->offsetExists($item->id);
    }

    /**
     * @param ItemEntity $item
     */
    public function retire(ItemEntity $item, $offset = null ){

        //echo "knows->retir($item->id)<br/>";
        if($this->disponible($item)){

            $this->offsetUnset($item->id);

        }
    }

    /**
     * @param ItemEntity $item
     * @return $this
     */
    public function ajoute(ItemEntity $item, $offset = null ){

        //echo "knows->ajoute($item->id);<br/>";

            $this->offsetSet($item->id,$item) ;



        return $this ;
    }
}