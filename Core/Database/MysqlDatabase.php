<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 16:54
 */

namespace Core\Database;

use Core\Debugger\Debugger;
use \PDO;
use PDOException;
use PDOStatement;

/**
 * Class MysqlDatabase
 * @package App
 */
class MysqlDatabase extends Database
{

    private $db_name;
    private $db_type;
    private $db_user;
    private $db_pass ;
    private $db_host ;

    private $pdo ;
    /**
     * MysqlDatabase constructor.
     * @param $db_name
     * @param string $db_user
     * @param string $db_host
     */
    public function __construct($db_name, $db_type='mysql', $db_user = 'root', $db_pass = "", $db_host='localhost')
    {
        $this->db_name = $db_name ;
        $this->db_type = $db_type ;
        $this->db_user = $db_user ;
        $this->db_pass = $db_pass ;
        $this->db_host = $db_host ;
    }

    /**
     * @return PDO
     */
    private function getPDO (): PDO
    {

        if(is_null($this->pdo)) {
            try {

                /* Connexion à une base MySQL avec l'invocation de pilote */
                $dsn = $this->db_type.':dbname='.$this->db_name.';host='.$this->db_host;

                $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            } catch (PDOException $e) {
                echo $dsn."<br/>";
                echo 'Connexion échouée : ' . $e->getMessage();
            }
        }

        return $this->pdo ;

    }

    /**
     * @param $statement
     * @param null $attrs
     * @return array
     */
    public function exec($statement, $attrs = null){

        if(is_null($attrs))
        {
            $res = $this->getPDO()->query( $statement);

            return [$res] ;
        }
        else
        {
            $req = $this->getPDO()->prepare($statement );
            $res = $req->execute($attrs);

            return [$req , $res];
        }


    }

    /**
     * @param $statement
     * @return bool
     */
    private function isMod($statement){

        if(stripos($statement,"update")===0 || stripos($statement,"insert")===0 || stripos($statement,"delete")===0 ){
            return true ;
        }
        return false ;
    }

    /**
     * @param PDOStatement $res
     * @param bool $one
     * @return array|mixed
     */
    public function result( PDOStatement $res,$one = false ){

        if($one)
            return $res->fetch();
        else
            return $res->fetchAll();
    }

    /**
     * @param PDOStatement $res
     * @param null $class_name
     * @return PDOStatement
     */
    public function setFetchMode( PDOStatement $res, $class_name = null ){

        if(is_null($class_name) )
        {
            $res->setFetchMode(PDO::FETCH_OBJ);
        }
        elseif(is_object($class_name))
        {
            $res->setFetchMode(PDO::FETCH_INTO, $class_name);
        }
        elseif(class_exists($class_name))
        {
            $res->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }
        else
        {
            $res->setFetchMode(PDO::FETCH_OBJ);
        }

        return $res ;
    }

    /**
     * @param $statement
     * @param null $class_name
     * @param bool $one
     * @return array|mixed|PDOStatement
     */
    public function query($statement, $class_name = null , $one = false){

        Debugger::getInstance()->sql($statement);

        list($res) = $this->exec($statement) ;

        if($this->isMod($statement) ){

            return $res ;
        }

        $res = $this->setFetchMode( $res,$class_name );

        return $this->result($res,$one);

    }

    /**
     * @param $statement
     * @param $attrs
     * @param null $class_name
     * @param bool $one
     * @return array|mixed
     */
    public function prepare($statement, $attrs,$class_name = null , $one = false)
    {
        Debugger::getInstance()->sql($statement, $attrs);

        list($req , $res) = $this->exec($statement, $attrs) ;

        if($this->isMod($statement) ){

            return $res ;
        }

        $req = $this->setFetchMode($req,$class_name);

       return $this->result($req,$one);
    }

    /**
     * @return string
     */
    public function lasInsertId()
    {
        return $this->getPDO()->lastInsertId();
    }

    /**
     * @return string
     */
    function dump()
    {
        $tables = $this->query("SHOW TABLES");

        $return = "\r"."CREATE DATABASE ".$this->db_name.";"."\r";

        $return .= "\r"."USE ".$this->db_name.";"."\r";

        foreach($tables as $table)
        {
            $name = $table->{'Tables_in_'.$this->db_name};

            $statement = Query::describe($name);

            $tb = $this->query('DESCRIBE ' . $name );

            $content = array();

            foreach($tb as $x => $column)
            {
                                                    $content[$x]  =  "`".$column->Field ."` ".$column->Type ;
                if($column->Key == 'PRI')           $content[$x] .=  ' PRIMARY KEY ';
                if($column->Null == 'No')           $content[$x] .=  " NOT NULL ";
                if($column->Null == 'Yes')          $content[$x] .=  " NULL ";
                if(trim($column->Default) != '')    $content[$x] .=  " DEFAULT ".$column->Default ;
                if(trim($column->Extra) != '')      $content[$x] .=  " ".$column->Extra ;
            }

            $return .= "\r"."-- ".strtoupper($name)."\r" ;
            $return .= "\r"." DROP Table IF EXISTS `".$name."` ; "."\r" ;
            $return .= "\r"." create table `".$name."` ( ";
            $return .= "\r".implode(",\r", $content);
            $return .= "\r"." ) ;"."\r" ;

            $statement = Query::from($name)->select('*');

            $recs = $this->query($statement); // 'select * from ' . $name);

            foreach($recs as $rec)
            {
                $set = $into = $values = array();

                foreach($rec as $col => $val)
                {
                    $set[] = " `$col` = '" . addslashes($val) . "' ";
                    $into[] = " `$col` ";
                    $values[] = "'" . addslashes($val) . "'";
                }

                $return .= "\r"."insert ignore into `$name`( " ;
                $return .= "\r".implode(", ", $into) ;
                $return .= "\r".") values (" ;
                $return .= "\r".implode(",\r", $values) ;
                $return .= "\r".");" ;
            }
        }

        return $return ;
    }
}