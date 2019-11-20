<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 20:53
 */

namespace App\Model\Heritage\Game\Inventaire;

use App\Model\Entity\Game\Inventaire\InventaireEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Heritage\Game\Item\ItemEquipementEntity;

/**
 * Class InventaireEquipementEntity
 * @package App\Model\Entity\Game\Inventaire
 */
class InventaireEquipementEntity extends InventaireEntity
{
    function __construct()
    {
        $this->container = array(

            "casque" => null ,
            "epaule" => null ,
            "amulette" => null ,
            "brassard" => null ,
            "botte" => null ,
            "torse" => null ,
            "bague" => null ,
            "ceinture" => null ,
            "arme" => null ,
            "bouclier" => null ,
            "jambe" => null ,
            "gant" => null

        );
    }

    static function getPlaces(){

        $tmp = new InventaireEquipementEntity();
        $emplacements = $tmp->getContainer();
        unset($tmp);

        return array_keys($emplacements);
    }

    function desequip(String $type ){

        //echo "equipement->desequip($type)<br/>";
        //if(isset($this->container[strtolower($type)]))
            $this->container[strtolower($type)] = null ;

        return $this ;
    }

    function equip(String $type, ItemEquipementEntity $piece){

        //echo "InventaireEquip->type : ".strtolower($type)."<br/>";
         //if(isset($this->container[strtolower($type)])) {
             $this->container[strtolower($type)] = $piece ;
             //echo "InventaireEquip->present : ".strtolower($type)."<br/>";
         //}

        return $this ;
    }

    function __set($name, $value)
    {
        if(isset($this->container[strtolower($name)])) {
            $this->container[strtolower($name)] = $value;
        }
        return $this ;
    }

    function __get($name)
    {
        return $this->container[strtolower($name)] ;
    }
}