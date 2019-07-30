<?php
namespace App\Model\Table\Game\Map ;

use App\Model\Table\Game\GameTable;
use Core\Database\QueryBuilder;

class MapTable extends GameTable
{
    function search( int $x , int $y ){

        $statement = QueryBuilder::init()->select('*')
            ->from($this->getTable())
            ->where('x = :x')
            ->where('y = :y')
        ;

        $attrs = array(
            'x' => $x,
            'y' => $y
        );

        return $this->request($statement,$attrs,true);
    }

    function getZone( $min_x , $max_x , $min_y , $max_y ){

        $statement = QueryBuilder::init()
            ->select('m.id','i.id as i_id','e.id as e_id','i.child_id','i.type','e.type as structure','m.x','m.y','e.name','t.id as terrain_id','t.name as terrain','e.img')
            ->from($this->getTable(),"m")
            ->join("personnage","p.position_id = m.id ","left","p")
            ->join("item","t.id = m.terrain_id ","left","t")
            ->join("inventaire","m.id = i.parent_id and i.rubrique = 'localisation'","left","i")
            ->join("item","e.id = i.child_id ","left","e")
            ->where('x between :min_x and :max_x')
            ->where('y between :min_y and :max_y')
        ;

        $attrs = array(
            'min_x' => $min_x ,
            'max_x' => $max_x ,
            'min_y' => $min_y ,
            'max_y' => $max_y
        );

        return $this->request($statement,$attrs,false);
    }
}