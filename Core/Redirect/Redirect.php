<?php
namespace Core\Redirect;

use Core\HTML\Env\Get;
use Core\Render\Url;
use Core\Request\Request;

class Redirect extends Url
{
    /**
     * @var Redirect
     */
    private static $_instance;


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
        self::location( self::buildQuery(Get::getInstance()->content('s')) );
    }

    /**
     *
     * @param null $_act
     * @param null $_ctl
     * @param null $dom
     */
    function redirect($_act = null ,$_ctl = null ,$dom = null )
    {
        if($this->verif($_act, $_ctl ,$dom ))
        {
            self::location("".$this->getPath($_act, $_ctl ,$dom));
        }
    }

    /**
     * lance une redirection depuis une entete web vers l'url passée en paramètre
     * @param $url
     */
    private static function location($url)
    {
        header("location:".$url);
        die;
    }
    /**
     * @Nom 		:	redirectPage
     * @api  		:	lance une redirection depuis une page html vers l'url passée en paramètre
     * @Paramètre 	:	une URL
     * @Retourne 	:	rien
     * @Créé le 	:	01/01/2010 par Sékou Traoré
     * @Ex d'appel	:	redirectPage('mon.php');
     * => redirige vers l'écran du monitoring
     **/
    function redirectPage($url)
    {
        echo('<meta http-equiv="refresh" content="0; URL='.$url.'">');
        die;
    }

    /**
     * @brief redirect or err
     * @return bool
     */
    public function send()
    {
        $_ctrl = is_null($this->dom) ? $this->getCtl() : $this->dom.".".$this->getCtl() ;
        $_act = $this->getAct();
        //$params = $this->getParams();

        if($this->verif($_act, $_ctrl ))
        {
            self::location($this->getPath());
        }

        return false ;
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->send();
    }
}