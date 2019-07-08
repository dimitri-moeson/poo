<?php


namespace App\Model\Entity\Game\Inventaire;


use App\Model\Entity\Game\Item\ItemStatEntity;
use App\Model\Entity\Journal;

/**
 * Class InventaireStatEntity
 * @package App\Model\Entity\Game\Inventaire
 */
class InventaireStatEntity extends InventaireEntity
{
    function getScore($type)
    {
        $res = 0 ;

        foreach ( $this->getContainer() as $stats )
        {
            if( $stats->type == "statistique" )
            {
                if( $stats->objet == $type)
                {
                    $res += $stats->getVal();
                }
            }
        }

        Journal::getInstance()->add("le score ".$type." est de ".$res."<br/>");

        return $res ;
    }
}