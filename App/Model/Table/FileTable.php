<?php


namespace App\Model\Table;


use App\Model\Entity\FileEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

class FileTable extends Table
{
    /**
     * @param $name
     * @return array|mixed
     */
    function named($name)
    {
        $statement = QueryBuilder::init()->select("*")->from($this->getTable())->where("nom = :nom");

        return  $this->request( $statement , array("nom"=> $name) , true,FileEntity::class );
    }

    /**
     * @param $type
     * @return array|mixed
     */
    function allOf($type)
    {
        $statement = QueryBuilder::init()->select("*")->from($this->getTable())->where("type = :type");

        return  $this->db->prepare($statement, array("type"=> $type),FileEntity::class );
    }

    /**
     * @param string $type
     * @param null $repo
     * @return mixed
     */
    protected function entityOf($type ="" , $repo = null )
    {
            $ucfTable = ucfirst($this->getTable());

            $ent = str_replace("Table","Entity",get_class($this));
            $ent = str_replace($ucfTable."Entity",$ucfTable.ucfirst($type)."Entity",$ent);

        if(!is_null($repo))
        {
            $ent = str_replace($ucfTable."\\".$ucfTable,$ucfTable."\\".$repo."\\".$ucfTable,$ent);
        }

            return $ent ;
    }
}