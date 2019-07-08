<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 19:10
 */

namespace Core\Database;

/**
 * Class QueryBuilder
 * @package Core\Database
 */
class QueryBuilder
{
    private $fields = [];
    private $conditions = [];
    private $from = [];
    private $jointure = [];

    private $describe = false ;
    private $insert = false ;
    private $update = false ;
    private $delete = false ;

    /**
     * @return $this
     */
    public function select():self{
        $this->fields = func_get_args();
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function describe($table):self{
        $this->describe = true ;
        $this->from($table);
        return $this ;
    }

    /**
     * @param $table
     * @return $this
     */
    public function insert($table):self{
        $this->insert = true ;
        $this->from($table);
        return $this ;
    }

    /**
     * @param $table
     * @return $this
     */
    public function update($table):self{
        $this->update = true ;
        $this->from($table);
        return $this ;
    }

    /**
     * @param $table
     * @return $this
     */
    public function delete($table):self{
        $this->delete = true ;
        $this->from($table);
        return $this ;
    }

    /**
     * @return $this
     */
    public function where():self{
        foreach(func_get_args() as $arg){
            $this->conditions[] = $arg;
        }
        return $this;
    }

    /**
     * @param $table
     * @param null $alias
     * @return $this
     */
    public function from($table, $alias = null):self{
        if(is_null($alias)){
            $this->from[] = $table;
        }else{
            $this->from[] = "$table AS $alias";
        }
        return $this;
    }

    /**
     * @param $table
     * @param $conditions
     * @param null $way
     * @param null $alias
     * @return $this
     */
    public function join($table,$conditions,$way = null, $alias = null ):self
    {
        if(is_null($alias)){
            $this->jointure[] = "$way JOIN $table ON $conditions ";
        }else{
            $this->jointure[] = "$way JOIN $table AS $alias ON $conditions ";
        }
        return $this;
    }

    /**
     * @param array $sql_parts
     * @return $this
     */
    public function set($sql_parts = array()):self {

        $this->set = $sql_parts;
        return $this ;

    }

    /**
     * @return QueryBuilder
     */
    public static function init():self{
        return new self();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $query = new self();
        return call_user_func_array([$query, $name], $arguments);

    }

    /**
     * @return string
     */
    public function __toString(){

        $where = null ;
        $join = null ;
        $init = null ;

        if($this->describe){
            $init = "DESCRIBE ".$this->from[0] ;

        }elseif($this->insert){
            $init = "INSERT INTO `".$this->from[0]."` SET ".implode(', ', $this->set) ;

        }elseif ($this->update){

            $init = "UPDATE `".$this->from[0]."` SET ".implode(', ', $this->set) ;

        }elseif ($this->delete){
            $init = 'DELETE FROM ' . $this->from[0];

        }else{
            $init = 'SELECT DISTINCT '. implode(', ', $this->fields)
                . ' FROM ' . implode(', ', $this->from);
        }
        if(!empty($this->jointure)) $join = ' '.implode(' ', $this->jointure);

        if(!empty($this->conditions)) $where = ' WHERE ' . implode(' AND ', $this->conditions);

        $sql = $init.$join.$where ;

        //echo $sql ;
        //echo "init => $init<br/>" ;
        //echo "join => $join<br/>" ;
        //echo "where => $where<br/>" ;

        return $sql ;
    }
}
