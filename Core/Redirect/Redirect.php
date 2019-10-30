<?php
namespace Core\Redirect;

use Core\HTML\Env\Get;
use Core\Path\Path;
use Core\Render\Url;
use Core\Request\Request;

class Redirect extends Url
{
    /**
     * @var Redirect
     */
    private static $_instance;

    /**
     * @return Redirect
     */
    public static function getInstance( )
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Redirect constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    static function reload()
    {
        $page = getenv('REQUEST_URI');
        $sec = "1";
        header("Refresh: $sec; url=$page");

        //self::location( self::buildQuery(Get::getInstance()->content()) );
    }

    /**
     *
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     */
    function redirect($_act = null ,$_ctl = null ,$dom = null )
    {
        if(Path::getInstance()->testActionPath($_act, $_ctl ,$dom))
        {
            self::location("".$this->getPath($_act, $_ctl ,$dom));
        }
    }

    /**
     * @api : lance une redirection depuis une entete web vers l'url passée en paramètre
     * @param $url
     */
    private static function location($url = null )
    {
        $clean = ($url ?? "/");

        header("location:".$clean );
        die;
    }

    /**
     * @api  		:	lance une redirection depuis une page html vers l'url passée en paramètre
     * @Nom 		:	redirectPage
     * @Paramètre 	:	une URL
     * @Retourne 	:	rien
     * @Ex d'appel	:	self::redirectPage('mon.php');
     * => redirige vers l'écran du monitoring
     **/
    private static function redirectPage($url = null )
    {
        $clean =  ($url ?? "/");

        echo '<meta http-equiv="refresh" content="0; URL='.$clean.'">' ;
        die;
    }

    /**
     * @brief redirect or err
     * @return bool
     */
    public function send()
    {
        if( Path::getInstance()->testActionPath($this->getAct(), $this->getCtl(), $this->getDom()))
        {
            $path = $this->getPath();

            if($path != "error...")
            {
                self::location($path);
                self::redirectPage($path);
            }
        }

        return false ;
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->getPath($this->getAct(), $this->getCtl(), $this->getDom());
    }
}