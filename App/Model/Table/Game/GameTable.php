<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 01/05/2019
 * Time: 19:18
 */
namespace App\Model\Table\Game ;

use Core\Database\QueryBuilder;
use Core\Debugger\Debugger;
use Core\Model\Table\Table;

class GameTable extends Table
{
    /**
     * Recupere la classe d'un objet pour l'affecter à son entité spécifique
     * @param $id
     * @return String
     */
    protected function getEntity($id, $repo = null ):?String
    {
        //Debugger::getInstance()->add("$id, $repo");

        $statement = QueryBuilder::init()->select("type")
            ->from($this->getTable())
            ->where("id = :id")
            ->where('deleteAt is null')
        ;

        $pClasse = $this->db->prepare($statement,array("id"=> $id),null,true );

        //Debugger::getInstance()->add($pClasse);

        if($pClasse !== false )
            return $this->entityOf($pClasse->type,$repo);

        return $this->entityOf("",$repo);

    }

    protected function entityOf($type ="" , $repo = null ){

        $ucfTable = ucfirst($this->getTable());

        $ent = str_replace("Table","Entity",get_class($this));  // get_called_class()
        $ent = str_replace( $ucfTable."Entity",$ucfTable.ucfirst($type)."Entity",$ent);  // get_called_class()

        if(!is_null($repo)){
            $ent = str_replace( $ucfTable."\\".$ucfTable,$ucfTable."\\".$repo."\\".$ucfTable,$ent);  // get_called_class()
        }

        return $ent ;
    }

    public function allOf($type){

        $statement = QueryBuilder::init()->select("*")
            ->from($this->getTable())
            ->where("type = :type")
            ->where('deleteAt is null')
        ;

        $ent = $this->entityOf($type);

        //Debugger::getInstance()->add($ent);

        return  $this->db->prepare($statement, array("type"=> $type),$ent );

    }

    /**
     * @param $id_perso
     * @return mixed
     */
    public function get(Int $id, $entity = null ){

        //$ent = $this->getEntity($id,$repo);

        //echo " ent : $ent<br/>";

        $tbl = $this->getTable();

        //echo " tbl : $tbl<br/>";

        //Debugger::getInstance()->add($id,$tbl,$repo,$ent);

        $statement = QueryBuilder::init()->select("*")
            ->from($tbl)
            ->where("id = :id")
            ->where('deleteAt is null')
        ;

        $result = $this->db->prepare($statement, array("id"=> $id), $entity ,true );

        // echo "resultGet<br/>";
        //var_dump($result);

        return $result ;

    }
}