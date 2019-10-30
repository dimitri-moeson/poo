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
    /**
     * @var array
     */
    private $groups = [];
    private $fields = [];
    private $conditions = [];
    private $from = [];
    private $jointure = [];

    private $describe = false;
    private $insert = false;
    private $update = false;
    private $delete = false;
    private $rand = false;
    private $limit ;
    private $offset ;


    /**
     * @return $this
     */
    public function select(): self
    {
        $this->fields = func_get_args();
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function describe($table): self
    {
        $this->describe = true;
        $this->from($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function insert($table): self
    {
        $this->insert = true;
        $this->from($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function update($table): self
    {
        $this->update = true;
        $this->from($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function delete($table): self
    {
        $this->delete = true;
        $this->from($table);
        return $this;
    }

    /**
     * @return $this
     * @params $conditions
     */
    public function where(): self
    {
        foreach (func_get_args() as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }

    /**
     * @param $table
     * @param null $alias
     * @return $this
     */
    public function from($table, $alias = null): self
    {
        if (is_null($alias)) {
            $this->from[] = "`$table`";
            $this->conditions[] = "`$table`.`deleteAt` IS NULL";
        } else {
            $this->from[] = "`$table` AS $alias";
            $this->conditions[] = "$alias.`deleteAt` IS NULL";
        }
        return $this;
    }

    public function limit($limit){

        $this->limit = $limit ;
        return $this ;
    }

    public function offset($offset){

        $this->offset = $offset ;
        return $this ;
    }

    /**
     * @param $field
     * @param string $sens
     * @return QueryBuilder
     */
    public function order($field, $sens = "ASC"): self
    {

        $this->orders[] = "`$field` $sens";

        return $this;
    }

    public function rand():self
    {
        $this->rand = true ;

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function orders(): self
    {
        foreach (func_get_args() as $arg) {
            if(is_array($arg)) {

                foreach ($arg as $field => $sens) {
                    $this->order($field,$sens);
                }

            } else {
                $this->orders[] = $arg;
            }
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
    public function join($table, $conditions, $way = null, $alias = null): self
    {
        if (is_null($alias)) {
            $this->jointure[] = "$way JOIN `$table` ON $conditions AND `$table`.`deleteAt` IS NULL ";
            $this->conditions[] = "`$table`.`deleteAt` IS NULL";
        } else {
            $this->jointure[] = "$way JOIN `$table` AS $alias ON $conditions AND $alias.`deleteAt` IS NULL ";
            $this->conditions[] = "$alias.`deleteAt` IS NULL";
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function group(): self
    {

        foreach (func_get_args() as $arg) {
            $this->groups[] = $arg;
        }
        return $this;
    }

    /**
     * @param array $sql_parts
     * @return $this
     */
    public function set($sql_parts = array()): self
    {

        $this->set = $sql_parts;
        return $this;

    }

    /**
     * @return QueryBuilder
     */
    public static function init(): self
    {
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
    public function __toString()
    {

        $where = null;
        $join = null;
        $init = null;
        $set = null;
        $order = null;
        $group = null;
        $limit = null ;
        $offset = null ;

        if ($this->describe) {
            $init = "DESCRIBE " . $this->from[0];
        } elseif ($this->insert) {
            $init = "INSERT INTO " . $this->from[0];
        } elseif ($this->update) {
            $init = "UPDATE " . $this->from[0];
        } elseif ($this->delete) {
            $init = 'DELETE FROM ' . $this->from[0];
        } else {
            $init = 'SELECT DISTINCT ' . implode(', ', $this->fields)
                . ' FROM ' . implode(', ', $this->from) . ' ';
        }

        if (!empty($this->set) && ($this->insert || $this->update)) {

            $set = " SET " . implode(', ', $this->set);
        }

        if (!$this->insert) {
            if (!empty($this->jointure)) $join = ' ' . implode(' ', $this->jointure);
            if (!empty($this->conditions)) $where = ' WHERE ' . implode(' AND ', $this->conditions);
            if (!empty($this->groups)) $group = ' GROUP BY ' . implode(', ', $this->groups);

            if(!$this->rand)
            {
                if (!empty($this->orders)) $order = ' ORDER BY ' . implode(', ', $this->orders);
            }
            elseif($this->rand)
            {
                $order = ' ORDER BY rand() ' ;
            }

            if (!empty($this->limit)) $limit = ' LIMIT ' . $this->limit ;
            if (!empty($this->offset)) $offset = ' OFFSET ' .$this->offset  ;
        }

        $sql = $init . $set . $join . $where . $group . $order . $limit . $offset ;

        return $sql;
    }
}
