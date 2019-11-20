<?php


namespace App\Model\Heritage\Game\Inventaire;

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