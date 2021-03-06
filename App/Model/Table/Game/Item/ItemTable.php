<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 18:28
 */

namespace App\Model\Table\Game\Item;

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Table\Game\GameTable;
use Core\Database\Query;
use Core\Database\QueryBuilder;

class ItemTable extends GameTable
{
    /**
     * @param Int $id
     * @param string $model
     * @return array|mixed
     */
    public function get($id, $model = ItemEntity::class )
    {
        $statement = Query::from("item")->select('*')
            ->where(" id = :id");

        return $this->db->prepare($statement,array( 'id' => $id),$model,  true );
    }

    /**
     * @param array $type
     *
     * @return array|mixed
     */
    public function typeListing( Array $types = array() ){

        /** @var  $statement */
        $statement = QueryBuilder::init()
            ->select('e.*')
            ->from("item",'e')
        ;

        $attrib = array();

        if(!empty($types)) {
            $r_type = array();

            foreach ($types as $x => $type) {
                $attrib['id_typ_' . $x] = $type;
                $r_type[$x] = ':id_typ_' . $x;
            }
            $statement->where( "e.type in (".implode(',', $r_type).") ");
        }

        //echo $statement ;
        //var_dump($attrib );
        return $this->request($statement , $attrib);
    }

    /**
     * @param $type
     * @return array|mixed
     */
    public function randomOne($type){

        $statement = QueryBuilder::init()->select("*")
            ->from($this->getTable())
            ->where("type = :type")
            ->rand()
            ->limit(1)
        ;

        $ent = $this->entityOf($type);

        return  $this->db->prepare($statement, array("type"=> $type),$ent,true );

    }

    /**
     * @param array $type
     *
     * @return array|mixed
     */
    public function itemListing( Array $types = array() , Array $rubriques = array(),$model = ItemEntity::class ){
        /** @var  $statement */
        $statement = QueryBuilder::init()
            ->select('e.*','i.id as inventaire_id','i.type as inventaire_type','i.rubrique as inventaire_rubrique', 'i.val','i.caract')
            ->from("item",'e')
            ->join('inventaire', ' i.child_id = e.id ', 'left', 'i')
        ;

        $attrib = array();

        if(!empty($types)) {
            $r_type = array();

            foreach ($types as $x => $type) {
                $attrib['id_typ_' . $x] = $type;
                $r_type[$x] = ':id_typ_' . $x;
            }
            $statement->where( "e.type in (".implode(',', $r_type).") ");
        }

        if(!empty($rubriques)) {
            $r_rub = array();

            foreach ($rubriques as $x => $rub) {
                $attrib['id_rub_' . $x] = $rub;
                $r_rub[$x] = ':id_rub_' . $x;
            }
            $statement->where( "i.rubrique in (".implode(',', $r_rub).") ");
        }

        //echo $statement ;

        return $this->request($statement , $attrib, false , $model );
    }

    /**
     * @param Int $id
     * @param Int|null $parent
     * @param null $rubrique
     * @param null $type
     * @param string $model
     * @return array|mixed
     */
    public function getWithInventaire(Int $id,Int $parent = null ,$rubrique = null ,$type = null, $model = ItemEntity::class){

        // var_dump(func_get_args());

        //echo "<br/>";

        $statement = QueryBuilder::init()
            ->select('e.*','i.id as inventaire_id','i.type as inventaire_type','i.rubrique as inventaire_rubrique', 'i.val', 'i.caract')
            ->from("item",'e')
            ->join('inventaire', ' i.child_id = e.id ', 'left', 'i')
            ->where("e.id = :id")
        ;

        $attrib = array( "id" => $id );

        if(!is_null($parent)){

            $statement->where('i.parent_id = :parent');
            $attrib['parent'] = $parent ;
        }

        if(!is_null($rubrique)){

            $statement->where( "i.rubrique = :rubrique ");
            $attrib['rubrique'] = $rubrique ;
        }

        if(!is_null($type)){

            $statement->where( "i.type = :type ");
            $attrib['type'] = $type ;
        }

         //echo $statement."<br/>";

        return $this->request($statement, $attrib, true, $model );
    }

    public function getInInventaire(Int $id_inventaire, $model = ItemEntity::class){

        $statement = QueryBuilder::init()
            ->select('e.*','i.id as inventaire_id','i.type as inventaire_type','i.rubrique as inventaire_rubrique', 'i.val', 'i.caract')
            ->from("item",'e')
            ->join('inventaire', ' i.child_id = e.id ', 'left', 'i')
            ->where("i.id = :id")
        ;

        $attrib = array( "id" => $id_inventaire );

        return $this->request($statement, $attrib, true, $model );
    }

    function getInventaire( $parent, Array $types = array() , Array $rubriques = array(), $model = ItemEntity::class){

        /** @var  $statement */
        $statement = QueryBuilder::init()
            ->select('e.*','i.id as inventaire_id','i.type as inventaire_type','i.rubrique as inventaire_rubrique', 'i.val','i.caract')
            ->from("item",'e')
            ->join('inventaire', ' i.child_id = e.id ', 'left', 'i')
        ;

        $attrib = array();

        if(!is_null($parent)){

            $statement->where('i.parent_id = :parent');
            $attrib['parent'] = $parent ;
        }

        if(!empty($types)) {
            $r_type = array();

            foreach ($types as $x => $type) {
                $attrib['id_typ_' . $x] = $type;
                $r_type[$x] = ':id_typ_' . $x;
            }
            $statement->where( "e.type in (".implode(',', $r_type).") ");
        }

        if(!empty($rubriques)) {
            $r_rub = array();

            foreach ($rubriques as $x => $rub) {
                $attrib['id_rub_' . $x] = $rub;
                $r_rub[$x] = ':id_rub_' . $x;
            }
            $statement->where( "i.rubrique in (".implode(',', $r_rub).") ");
        }

        //echo $statement ;

        return $this->request($statement , $attrib, false , $model );

    }
}