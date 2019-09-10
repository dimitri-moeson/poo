<?php
namespace Core\Request ;

use Core\Controller\Javascript;
use Core\Controller\Picture;
use Core\Controller\Stylesheet;
use Core\Debugger\Debugger;
use Core\HTML\Env\Get;

/**
 * Class Request
 * @package Core\Request
 */
class Request
{
    /**
     * @var Request
     */
    private static $instance;

    /**
     * @var string
     */
    private $ctrl_name;

    /**
     * @var
     */
    private $ctrl ;

    /**
     * @var
     */
    private $action;

    /**
     * @var array
     */
    private $page = array();

    /**
     * @var string
     */
    private $slug;

    /**
     * @return Request
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance ;
    }

    /**
     * Request constructor.
     * singleton
     */
    private function __construct()
    {
        if(DEBUG) {

            if(Get::getInstance()->has('c')) {

                $s = Get::getInstance()->val('c');

                if ($s === 'debug') {

                    Debugger::getInstance()->stylesheet();
                    die;
                }
            }

            if(Get::getInstance()->has('s')) {

                $s = Get::getInstance()->val('s');

                if ($s === 'debug') {

                    Debugger::getInstance()->javascript();
                    die;
                }
            }
        }

        if(Get::getInstance()->has('js')) {

            $js = Get::getInstance()->val('js');

            $javascript = new Javascript($js);
            $javascript->read();
            die;
        }

        if(Get::getInstance()->has('css')) {

            $css = Get::getInstance()->val('css');

            $stylesheet = new Stylesheet($css);
            $stylesheet->read();
            die;
        }

        if(Get::getInstance()->has('img')) {

            $img = Get::getInstance()->val('img');

            $picture = new Picture($img);
            $picture->read();
            die;
        }

        $p = getenv('REQUEST_URI')=="/" ? 'default/index' :  getenv('REQUEST_URI') ;//Get::getInstance()->val('p') ?? 'default.index';

        $this->page = explode('/', ltrim($p,"/") );

        Debugger::getInstance()->app("page",$this->getPage());

        if(count($this->page)== 1 )
        {
            $this->ctrl_name = '\App\Controller\\'.ucfirst($this->page[0]).'Controller';
            $this->ctrl = $this->page[0] ?? "default";
            $this->action = "index";
            $this->slug = null ;
        }
        elseif(count($this->page)== 2 )
        {
            $this->ctrl_name = '\App\Controller\\'.ucfirst($this->page[0]).'Controller';
            $this->ctrl = $this->page[0] ?? "default";
            $this->action = $this->page[1] ?? "index";
            $this->slug = null ;
        }
        elseif(count($this->page)== 3 )
        {
            $this->ctrl_name = '\App\Controller\\'.ucfirst($this->page[0]).'\\'.ucfirst($this->page[1]).'Controller';
            $this->ctrl = $this->page[1] ?? "default";
            $this->action = $this->page[2] ?? "index";
            $this->slug = null ;
        }
        elseif(count($this->page)== 4 )
        {
            $this->ctrl_name = '\App\Controller\\'.ucfirst($this->page[0]).'\\'.ucfirst($this->page[1]).'Controller';
            $this->ctrl = $this->page[1] ?? "default";
            $this->action = $this->page[3] ?? "index";
            $this->slug = $this->page[2] ?? "1";
        }
    }

    /**
     * @return bool
     */
    public function is_callable($ctrl = null , $act = null ) {

        $ctrl_name = $ctrl ?? $this->getCtrlName();

        if(isset($ctrl_name))
        {
            if (class_exists($ctrl_name,true ))
            {
                $action = $act ?? $this->getAction();

                if (is_callable(array($ctrl_name, $action)))
                {
                    return true;
                }
            }
        }

        return false ;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getCtrl()
    {
        return $this->ctrl;
    }

    /**
     * @return string
     */
    public function getCtrlName(): string
    {
        return $this->ctrl_name;
    }

    /**
     * @return array
     */
    public function getPage(): array
    {
        return $this->page;
    }

    /**
     * @param string $slug
     * @return Request
     */
    public function setSlug(string $slug = null ): Request
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}