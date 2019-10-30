<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 16:54
 */

namespace Core\Database;

use Core\Config;
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
        if(is_null($this->pdo))
        {
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

        try {
            if (is_null($attrs)) {
                $res = $this->getPDO()->query($statement);

                return [$res];
            } else {

                $req = $this->getPDO()->prepare($statement);

                foreach ($attrs as $field => $value ){

                    if(is_numeric($value))
                    {
                        $req->bindValue(":" . $field, $value, PDO::PARAM_INT);
                    }
                elseif(is_string($value))
                    {
                        $req->bindValue(":" . $field, $value, PDO::PARAM_STR);
                    }

                }

                $res = $req->execute(); // $attrs

                return [$req, $res];
            }
        }
        catch (PDOException $e) {

                echo $statement."<br/>";
                if(!is_null($attrs))
                {
                    echo print_r($attrs,1)."<br/>";
                }

                echo 'requete échouée : ' . $e->getMessage()."<br/>";
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

        try {
            if($one)
            return $res->fetch();
        else
            return $res->fetchAll();
        }
        catch (PDOException $e) {
            echo 'fetch échouée : ' . $e->getMessage()."<br/>";

        }
    }

    /**
     * @param PDOStatement $res
     * @param null $class_name
     * @return PDOStatement
     */
    public function setFetchMode( PDOStatement $res, $class_name = null ){
        try {

            if (is_null($class_name)) {
                $res->setFetchMode(PDO::FETCH_OBJ);
            } elseif (is_object($class_name)) {
                $res->setFetchMode(PDO::FETCH_INTO, $class_name);
            } elseif (class_exists($class_name)) {
                $res->setFetchMode(PDO::FETCH_CLASS, $class_name);
            } else {
                $res->setFetchMode(PDO::FETCH_OBJ);
            }

            return $res;
        }  catch (PDOException $e) {
                echo 'setMode échouée : ' . $e->getMessage()."<br/>";

            }
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
     * @param $table
     * @return array
     */
    function describe($table){

        $tb = $this->query('DESCRIBE ' . $table) ; // Query::describe($name);

        $ct = array();

        foreach ($tb as $x => $column) {

            $ct[$column->Field]  = [
                "type"      =>  $column->Type ,
                "nullable"  => ($column->Null == 'No' ? false : ($column->Null == 'Yes' ? true : false)) ,
                "primary"   => ($column->Key == 'PRI' ? true : false) ,
                "extra"     =>  $column->Extra ,
                "default"   =>  $column->Default
            ];
        }

        return $ct ;

    }

    /**
     *
     */
    function generate(){

        $tables = $this->query("SHOW TABLES");
        foreach($tables as $table) {
            $name = $table->{'Tables_in_' . $this->db_name};

            $tb = $this->describe($name);

            $setter = $getter = $variables = "" ;

            foreach ($tb as $col => $format){

                $variables .= " \t\tprivate $".$col." ; \n";

                $getter .=  "  \t\tpublic function get".ucfirst($col)."()
        {
            return $"."this->".$col." ;
        } \n\n";

                $setter .=  "  \t\tpublic function set".ucfirst($col)."($".$col.")
        {
            $"."this->".$col." = $".$col.";
            return $"."this ;
        } \n\n";
            }

            $content = "<?php 
            
    class ".ucfirst($name)."Entity extends Entity
    {
        $variables
        
        $getter
       
        $setter
    }
?>";
            file_put_contents( ROOT."/generate/".ucfirst($name)."Entity.php",$content);
        }

    }

    /**
     * @return string
     */
    function dump()
    {
        $tables = $this->query("SHOW TABLES");

        $creat_part = "";
        $alter_part = "";
        $inser_part = "";

        $return = "\r"."CREATE DATABASE ".$this->db_name.";"."\r";

        $return .= "\r"."USE ".$this->db_name.";"."\r";

        foreach($tables as $table) {
            $name = $table->{'Tables_in_' . $this->db_name};

            /// ------------------------- création table

            $tb = $this->describe($name);

            $content = array();

            foreach ($tb as $col => $format){

                                       $content[$col] = "`" . $col . "` " . $format["type"];
                if($format["primary"]) $content[$col] .= ' PRIMARY KEY ';
                                       $content[$col] .= $format["nullable"] ? " NULL " : " NOT NULL ";

                if (trim($format["default"]) != '') {
                    if ($format["default"] != 'current_timestamp()') {
                        $content[$col] .= " DEFAULT '" . $format["default"] . "'";
                    } else {
                        $content[$col] .= " DEFAULT " . $format["default"] ;
                    }
                }

                $content[$col] .= $format["extra"] ;
            }

            $creat_part .= "\r" . "-- table " . strtoupper($name) . " -- \r";
            $creat_part .= "\r" . " DROP Table IF EXISTS `" . $name . "` ; " . "\r";
            $creat_part .= "\r" . " create table `" . $name . "` ( ";
            $creat_part .= "\r" . implode(",\r", $content);
            $creat_part .= "\r" . " ) ;" . "\r";

            /// ------------------------- clés étrangeres

            $recs = $this->query( "SELECT
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                REFERENCED_TABLE_SCHEMA = '".$this->db_name."'
                AND REFERENCED_TABLE_NAME = '" . $name . "';");

            if(!empty($recs)) {
                $alter_sql = "--- " . $name . "";

                foreach ($recs as $x =>  $rec) {
                    $alter_sql = "alter table `" . $rec->TABLE_NAME . "`";
                    $alter_sql .= " add constraint `" . $rec->CONSTRAINT_NAME . "`";
                    $alter_sql .= " foreign key(`" . $rec->COLUMN_NAME . "`)";
                    $alter_sql .= " references `" . $rec->REFERENCED_TABLE_NAME . "`(`" . $rec->REFERENCED_COLUMN_NAME . "`)";
                }

                $alter_part .= "\r" . $alter_sql.";" ;

            }

            /// ------------------------- enregistrement

            $recs = $this->query('select * from ' . $name);

            $chaines = array();

            foreach ($recs as $r => $rec) {
                $set = $into = $values = array();

                foreach ($rec as $col => $val) {
                    $into[] = " `$col` ";
                    if(is_numeric($val)) {
                        $values[] = $val;
                        $set[] = " `$col` = " . addslashes($val) . " ";
                    }else {
                        $values[] = "'" . addslashes($val) . "'";
                        $set[] = " `$col` = '" . addslashes($val) . "' ";
                    }
                }

                if ($r === 0) {
                    $inser_part .= "\r" . "insert ignore into `$name`(" . implode(", ", $into) . ") values ";
                }
                $chaines[] = "(" . implode(",", $values) . ")";
            }

            $inser_part .= "\r" . implode(",\r", $chaines) . ";" . "\r";

        }

        $return .= $creat_part ;
        $return .= $alter_part ;
        $return .= $inser_part ;

         return $return ;
    }
}