<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 18:57
 */

namespace Core\Model\Table;


use Core\Database\Database;
use Core\Database\MysqlDatabase;
use Core\Database\Query;
use Core\Database\QueryBuilder;
use Core\Model\Entity\Entity;

class Table
{
    protected $table ;

    protected $db ;

    /**
     * Table constructor.
     * @param Database|null $db
     */
    public function __construct(MysqlDatabase $db = null )
    {
        $this->db = $db ;

        $this->getTable();
    }

    /**
     * @return mixed
     */
    protected function getTable()
    {
        if(is_null($this->table))
        {
            $class_name = explode('\\',get_called_class());
            $this->table = str_replace("table","",strtolower(end($class_name)));
        }
        return $this->table;
    }

    /**
     * @param $statement
     * @param null $attrs
     * @return array|mixed
     */
    public function request($statement, $attrs = null, $one = false , $model = null  ){

        $ent = $model ?? str_replace("Table","Entity",get_class($this));  // get_called_class()

        if(is_null($attrs))
        {
            return $this->db->query( $statement , $ent , $one);
        }
        else
        {
            return $this->db->prepare($statement, $attrs, $ent,$one );
        }
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $statement = Query::select('*')->from($this->getTable());
        return $this->request( $statement );
    }

    /**
     * @param $key
     * @param $value
     * @param null $statement
     * @param null $attrs
     * @return array
     */
    public function list($key,$value, $statement = null, $attrs = null  )
    {
        if(is_null($statement))
            $records = $this->all();
        else
            $records = $this->request($statement , $attrs);

        $return = array();

        foreach ($records as $record)
            $return[$record->$key] = $record->$value ;

        return $return;
    }

    /**
     * "select * from ".$this->getTable()." where id = :id ;"
     * @param $id
     * @return Entity
     */
    public function find($id) {

        $statement = Query::from($this->getTable())
            ->select('*')
            ->where(" id = :id")
        ;

        return $this->request($statement, array( 'id' => $id), true );

    }

    /**
     * @param $fields
     * @param null $id
     * @return array
     */
    private function formatSet($fields, $id = null){

        $sql_parts = array();
        $attrs = is_null($id) ? array() : array("id" => $id );

        foreach( $fields as $k => $v){

            if($k != "submit"){
                $sql_parts[$k] = " `$k` = :$k ";
                $attrs[$k] = $v;
            }

        }

        if(is_null($id)){

            $sql_parts['createAt'] = " createAt = :createAt ";
            $attrs['createAt'] = date("Y-m-d H:i:s");

        }

        $sql_parts['updateAt'] = " updateAt = :updateAt ";
        $attrs['updateAt'] = date("Y-m-d H:i:s");

        return array ( $sql_parts , $attrs );
    }

    /**
     * "INSERT INTO ".$this->getTable()." set ". implode(",", $sql_parts)
     * @param $fields
     * @return array|mixed
     */
    public function create( $fields ){

        list( $sql_parts , $attrs ) = $this->formatSet($fields);

        $statement = Query::insert($this->getTable())->set($sql_parts);

        return $this->request($statement, $attrs, true );

    }

    /**
     * "DELETE FROM ".$this->getTable()." where id = :id ;"
     * @param $id
     * @return array|mixed
     */
    public function delete($id )
    {
        $statement = Query::delete($this->getTable())->where("id = :id");
        return $this->request($statement, array( 'id' => $id) , true );
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function archive($id){

        list( $sql_parts , $attrs ) = $this->formatSet(array(
            'deleteAt' => date("Y-m-d H:i:s")
        ) , $id);

        $statement = QueryBuilder::init()->update($this->getTable())->where("id = :id")->set($sql_parts);

        return $this->request($statement, $attrs, true );

    }

    /**
     * "UPDATE ".$this->getTable()." set ". implode(",", $sql_parts) ." where id = :id ;"
     * @param $id
     * @param $fields
     * @return array|mixed
     */
    public function update($id, $fields ){

        list( $sql_parts , $attrs ) = $this->formatSet($fields , $id);

        $statement = Query::update($this->getTable())
            ->set($sql_parts)
            ->where("id = :id");

        return $this->request($statement, $attrs, true );
    }
}