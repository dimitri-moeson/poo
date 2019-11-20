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

        $ent = $model ?? str_replace("Table","Entity",get_class($this));

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
    public function list($key,$value, $attrs = null , $statement = null )
    {
        if(is_null($attrs) && is_null($statement) )
        {
            $records = $this->all();
        }
        elseif(is_null($statement) && !is_null($attrs))
        {
            $records =$this->findBy($attrs);
        }
        elseif(!is_null($statement) && is_null($attrs))
        {
            $records = $this->request($statement);
        }
        else
        {
            $records = $this->request($statement, $attrs);
        }
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
            ->where(" id = :id")->limit(1)
        ;

        return $this->request($statement, array( 'id' => $id), true );

    }

    /**
     * @param $id
     * @return bool
     */
    public function exists($id) {

        $statement = Query::from($this->getTable())
            ->select('count(*) as existence')
            ->where(" id = :id")->limit(1)
        ;

        $mot = $this->request($statement, array( 'id' => $id), true );

        return $mot->existence > 0;
    }

    /**
     * @param array $attr
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array|mixed
     */
    public function findBy(Array $attr = array(),Array $orderBy = null, $limit = null, $offset = null)
    {
        $statement = Query::from($this->getTable())
            ->select('*');

        if($statement instanceof QueryBuilder) {

            foreach ($attr as $key => $val )
                $statement->where(" `".$this->getTable()."`.`$key` = :$key ");

            if(!is_null($orderBy)) {
                if (is_array($orderBy)) {
                    foreach ($orderBy as $ord => $sens) {

                        if (is_numeric($sens))
                            $statement->order($ord);
                        else
                            $statement->order($ord, $sens);
                    }
                } else {
                    $statement->orders($orderBy);
                }
            }
            $statement
                ->limit($limit)
                ->offset($offset);
        }

        return $this->request($statement, $attr, false );
    }

    /**
     * @param array $attr
     * @return bool
     */
    public function existsBy(Array $attr = array())
    {
        $statement = Query::from($this->getTable())
            ->select('count(*) as existence')
        ;
        if($statement instanceof QueryBuilder) {

            foreach ($attr as $key => $val )
                $statement->where(" `".$this->getTable()."`.`$key` = :$key ");
        }

        $mot = $this->request($statement, $attr, true  );

        return $mot->existence > 0 ;
    }

    /**
     * @param array $attr
     * @return array|mixed
     */
    public function findOneBy(Array $attr = array()){

        $statement = Query::from($this->getTable())
            ->select('*')->limit(1);

        foreach ($attr as $key => $val )
            $statement->where(" `".$this->getTable()."`.`$key` = :$key ");

        return $this->request($statement, $attr, true  );
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

            $sql_parts['createAt'] = " `createAt` = :createAt ";
                $attrs['createAt'] = date("Y-m-d H:i:s");

        }

        $sql_parts['updateAt'] = " `updateAt` = :updateAt ";
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

        try{
            $this->request($statement, $attrs, true );

            return true ;
        }
        catch (\Exception $e){
            die($e->getMessage());
        }

    }

    /**
     * "DELETE FROM ".$this->getTable()." where id = :id ;"
     * @param $id
     * @return array|mixed
     */
    public function delete($id )
    {
        $statement = Query::delete($this->getTable())->where("id = :id");

        try{
            $this->request($statement, array( 'id' => $id) , true );

            return true ;
        }
        catch (\Exception $e){
            die($e->getMessage());
        }
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

        try{
            $this->request($statement, $attrs, true );

            return true ;
        }
        catch (\Exception $e){
            die($e->getMessage());
        }
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

        try{
            $this->request($statement, $attrs, true );
            //echo "edition table class";
            return true ;
        }
        catch (\Exception $e){
            die($e->getMessage());
        }
    }
}