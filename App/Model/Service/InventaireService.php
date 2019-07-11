<?php

namespace App\Model\Service;

use App\Model\Entity\Game\Inventaire\InventaireEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Item\ItemEquipementEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\Debugger\Debugger;
use Core\Render\Render;

class InventaireService extends Service
{
    /**
     * InventaireService constructor.
     */
    public function __construct()
    {
        try {
            parent::__construct();

            $this->loadModel("Game\Inventaire\Inventaire");

        } catch (\Exception $e) {
            var_dump($e);
        }
    }


    public function table(String $name = "Inventaire", Array $sacoche = array())
    {
        return Render::getInstance()->block("inventaire",array(

            "sacoche" => $sacoche,
            "inventaire" => $name
        ));
    }

    /**
     * @param String $name
     * @param InventaireEntity $inventaire
     * @return String|null
     */
    public function listing(String $name = "Inventaire", InventaireEntity $inventaire): ?String
    {
        $return = "" ; //("<ul>")

        foreach ($inventaire->getContainer() as $i => $item) {

            if ($item instanceof ItemEntity) {

                $return .= "<div class='col-sm-3'>".Render::getInstance()->block("item.btn",array(

                    "equip" => $item,
                    "block" => $name,

                ))."</div>";
            }

        }

        return $return;
    }

    function recordItemInInventaire( $parent , ItemEntity $item){

        if (isset($item->record) && $item->record === true) {

            $datas = array(

                "parent_id" => $parent ,
                "child_id" => $item->id,
                "type" => $item->inventaire_type,
                "rubrique" => $item->inventaire_rubrique,
                "val" => $item->val,
                "caract" => $item->caract
            );

            Debugger::getInstance()->add($datas);

            if ($this->InventaireBase instanceof InventaireTable)
            {
                /**
                 * if (!isset($item->val) || !is_null($item->val) || $item->val == 0 || empty($item->val))
                    $this->InventaireBase->delete($item->inventaire_id);
                else
                 */
                if (isset($item->inventaire_id) && !is_null($item->inventaire_id))
                {
                    $this->InventaireBase->update($item->inventaire_id, $datas);
                }
                else
                {
                    $this->InventaireBase->create($datas);
                }
            }
        }


    }

    /**
     * enregistre inventaire ...
     * @param InventaireEntity $inventaire
     * @param Int $personnage_id
     */
    function saveInventaire(InventaireEntity $inventaire, Int $personnage_id)
    {
        Debugger::getInstance()->add(" saveInventaire " . $inventaire->count());
        Debugger::getInstance()->add($inventaire);

        foreach ($inventaire->getContainer() as $x => $item) {

            Debugger::getInstance()->add($x, $item);

            if ($item instanceof ItemEntity) {

                Debugger::getInstance()->add("found instance $x ");

                $this->recordItemInInventaire($personnage_id,$item);

            } else {
                Debugger::getInstance()->add("bad instance $x ");
            }
        }
    }

}