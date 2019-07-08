<?php


namespace App\Model\Entity\Game\Inventaire;


class InventaireArmementEntity extends InventaireEquipementEntity
{
    function __construct()
    {
        $this->container = array(

            "droite" => null ,
            "gauche" => null ,
            "mains" => null ,
            "port" => null
        );
    }
}