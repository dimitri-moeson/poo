<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 19:56
 */
namespace App\Model\Table\Game\Inventaire;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Table\Game\GameTable;
use Core\Database\QueryBuilder;

class InventaireTable extends GameTable
{
    /**
     * @param null $parent_id
     * @param null $i_rubrique
     * @param null $i_type
     * @param array $e_types
     * @param null $model
     * @return array|mixed
     */
    public function itemListing($parent_id = null , $i_rubrique = null , $i_type = null , $e_types = array() , $model = ItemEntity::class ){

        $statement = QueryBuilder::init()
            ->select('e.*','i.id as inventaire_id','i.type as inventaire_type','i.rubrique as inventaire_rubrique', 'i.val', 'i.caract')
            ->from("item",'e')
            ->join('inventaire', ' i.child_id = e.id ', 'inner', 'i')
        ;

        $attrib = array();

        if(!is_null($parent_id)) {
            $attrib['parent_id'] = $parent_id;
            $statement->where("i.parent_id = :parent_id");
        }

        if(!is_null($i_rubrique)) {
            $attrib['rubrique'] = $i_rubrique;
            $statement->where("i.rubrique = :rubrique");
        }

        if(!is_null($i_type)) {
            $attrib['type'] = $i_type;
            $statement->where("i.type = :type");
        }

        if(!empty($e_types)) {
            $r_type = array();

            foreach ($e_types as $x => $type) {
                $attrib['id_typ_' . $x] = $type;
                $r_type[$x] = ':id_typ_' . $x;
            }
            $statement->where( "e.type in (".implode(',', $r_type).") ");
        }

        return $this->db->prepare($statement , $attrib, $model, false) ;
    }

    /**
     * @param $id
     * @param $rubrique
     * @return array
     */
    public function rubriqueListing($id,$rubrique ){

        $statement = QueryBuilder::init()
            ->select('id','child_id' ,'type','rubrique', 'val','caract')
            ->from("inventaire")
            ->where("parent_id = :id","rubrique = :rub");

        $attrib = array("id" => $id ,"rub" => $rubrique );

        $records = $this->db->prepare($statement , $attrib);

        $return = array();

        foreach ($records as $x => $record)
            $return[$x] = array(

                "type" => $record->type ,
                "val" => $record->val ,
                'rubrique' => $record->rubrique,
                'inventaire_id' => $record->id,
                'item_id' => $record->child_id,
            ) ;

        //Debugger::getInstance()->add($return);
        return $return ;

    }

    /**
     * @param $id
     * @param $rubrique
     * @param $type
     * @return array
     */
    public function listing($id,$rubrique, $type ){

        $statement = QueryBuilder::init()
            ->select('child_id' ,'type')
            ->from("inventaire")
            ->where("parent_id = :id","rubrique = :rub","type = :type");
        $attrib = array("id" => $id ,"rub" => $rubrique, "type" => $type );

        //Debugger::getInstance()->add($attrib);

        return $this->list('child_id' ,'type',$statement,$attrib);

    }
}