<?php


namespace Core\Model\Entity;


use Core\Database\Database;
use Core\Database\MysqlDatabase;

class SqlEntity extends Entity
{
    private $id ;

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

    public function getId(){

        return $this->id ;
    }
}