<?php


namespace Core\Debugger ;


use App\Model\Entity\Game\Personnage\PersonnageEntity;
use Error;
use ErrorException;
use Exception;

/**
 * Class Debugger
 * @package Core\Debugger
 */
class Debugger
{
    /** @var PersonnageEntity à mettre dans APP, pas dans Core !!! */
    private $personnage ;

    /** @var  */
    private $history = array();

    /**
     * @var array
     */
    private $sql = array();

    /**
     * @var array
     */
    private $appli = array();

    /** @var  */
    private static $_instance ;

    /** @var int  */
    private $limit_trace = 3 ;

    /**
     * @return Debugger
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Debugger constructor.
     */
    private function __construct()
    {
        ini_set('error_reporting', E_ALL);
        error_reporting(E_ALL);
        ini_set("display_errors", (DEBUG ? "on" : "off"));
        //set_error_handler(array($this,"customError"),E_ALL);

        //register_shutdown_function( array($this,"check_for_fatal") );
        //set_error_handler( array($this,"log_error") ,E_ALL);
        //set_exception_handler( array($this,"log_exception") );
    }

    /**
     * readfile
     */
    public function javascript(){

        header('Content-type: application/javascript');
        readfile(ROOT.'/Core/Debugger/Debugger.js');
        die;
    }

    /**
     * readfile
     */
    public function stylesheet(){

        header('content-type: text/css');
        header('Cache-Control: max-age=31536000, must-revalidate');
        readfile(ROOT.'/Core/Debugger/Debugger.css');
        die;
    }

    /**
     *
     */
    public function add(){

        $args = func_get_args();

        foreach ($args as $str) {

            //echo print_r($str,1)."<br/>";
            $this->history[] = array(

                "content" => $str,
                "trace" => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,self::getLimitTrace())

            );
        }
    }

    /**
     * @param $statement
     * @param array $attrs
     */
    public function sql($statement, $attrs = array() ){

            $this->sql[] = array(

                "content" => $statement,
                "attrib" => $attrs ,
                "trace" => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,self::getLimitTrace())

            );
    }

    /**
     * @param PersonnageEntity $personnage
     */
    public function perso(PersonnageEntity $personnage){

        $this->personnage = $personnage;
    }

    /**
     * @param $index
     * @param $value
     */
    public function app($index, $value){
        $this->appli[$index] = $value;
    }

    /**
     *
     */
    public function view()
    {
        if(DEBUG) require_once ROOT . '/Core/Debugger/Debugger.html.php';
    }

    /**
     * Gestionnaire d'erreurs
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool|void
     */
    function customError($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // Ce code d'erreur n'est pas inclus dans error_reporting(), donc il continue
            // jusqu'au gestionaire d'erreur standard de PHP
            return;
        }



        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>ERREUR</b> [$errno] $errstr<br />\n";
                echo "  Evenement sur la ligne $errline dans le fichier $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Arrêt...<br />\n";
                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>ALERTE</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>AVERTISSEMENT</b> [$errno] $errstr<br />\n";
                break;

            default:
                echo "Type d'erreur inconnu : [$errno] $errstr<br />\n";
                break;
        }

        /* Ne pas exécuter le gestionnaire interne de PHP */
        return true;
    }

    /**
     * Error handler, passes flow over the exception logger with new ErrorException.
     * @param $num
     * @param $str
     * @param $file
     * @param $line
     * @param null $context
     */
    function log_error( $num, $str, $file, $line, $context = null )
    {
        $this->log_exception( new ErrorException( $str, 0, $num, $file, $line ) );
    }

    /**
     * Uncaught exception handler.
     * @param Exception $e
     * @param Error $e
     * @param ErrorException $e
     */
    function log_exception( $e )
    {
        if (!(error_reporting() & $e->getCode())) {
            // Ce code d'erreur n'est pas inclus dans error_reporting(), donc il continue
            // jusqu'au gestionaire d'erreur standard de PHP
            return;
        }

        if ( DEBUG )
        {
            $errno = $e->getCode();
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();

            switch ($errno) {
                case E_USER_ERROR:
                    echo "<b>ERREUR</b> [$errno] $errstr<br />\n";
                    echo "  Evenement sur la ligne $errline dans le fichier $errfile";
                    echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                    echo "Arrêt...<br />\n";
                    exit(1);
                    break;

                case E_USER_WARNING:
                    echo "<b>ALERTE</b> [$errno] $errstr<br />\n";
                    break;

                case E_USER_NOTICE:
                    echo "<b>AVERTISSEMENT</b> [$errno] $errstr<br />\n";
                    break;

                default:
                    echo "Type d'erreur inconnu : [$errno] $errstr<br />\n";
                    break;
            }

            print "<div style='text-align: center;'>";
            print "<h2 style='color: rgb(190, 50, 50);'>Exception Occured:</h2>";
            print "<table style='width: 800px; display: inline-block;'>";
            print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class( $e ) . "</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
            print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>".str_replace(ROOT,"",$e->getFile())."</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
            print "</table><hr/>";
            print "<table>";

            foreach( $e->getTrace() as $x => $trace){

                print "<tr style='background-color:rgb(".( $x%2 == 0 ? '230,230,230' :'240,240,240').");'>
                            <th>File</th><td>".str_replace(ROOT,"",$trace["file"])."</td>
                            <th>Line</th><td>{$trace["line"]}</td>
                            <th>function</th><td>{$trace["function"]}</td>
                            <th>args</th><td><pre>". print_r($trace["args"],1)." </pre></td></tr>";
            }
            print "</table></div>";
            //echo "<pre>".print_r($e,1)."</pre>";
        }
        else
        {

            $message = "Type: " . get_class( $e ) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";
            file_put_contents( ROOT . "/tmp/log/".date("Ymd")."-exceptions.log", $message . PHP_EOL, FILE_APPEND );
            header( "Location:?p=error" );
        }

        exit();
    }

    /**
     * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
     */
    function check_for_fatal()
    {
        $error = error_get_last();
        if ( $error["type"] == E_ERROR )
            $this->log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
    }

    /**
     * @return int
     */
    public function getLimitTrace(): int
    {
        return $this->limit_trace;
    }
}