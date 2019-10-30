<?php
namespace Core\Request ;

use Core\Controller\Javascript;
use Core\Controller\Picture;
use Core\Controller\Stylesheet;
use Core\Debugger\Debugger;
use Core\HTML\Env\Get;
use Core\Path\Path;

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
     * @var string|null
     */
    private $dom;

    /**
     * singleton
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
        $p = getenv('REDIRECT_URL') ==="/" ? '/default/index' :  getenv('REDIRECT_URL') ;

        Debugger::getInstance()->app("request_uri",$p);

        $page = explode('/', ltrim($p,"/") );

        Debugger::getInstance()->app("page",$page);

        if(DEBUG && $page[0]=="debug") {
            $this->debugger($page);
        }
        /**
         * module habillage
         */
        elseif($page[0] === "js") {

                $javascript = new Javascript($page[1]);
                $javascript->read();
                die;
            }
        elseif($page[0] === "css") {

                $stylesheet = new Stylesheet($page[1]);
                $stylesheet->read();
                die;
            }
        elseif($page[0] === "img") {

                $picture = new Picture( $page[1]);
                $picture->read();
                die;
            }
        /**
         * page request
         */
        else
        {
            $this->page = $this->dispatch($page);
        }
    }

    /**
     * module debug
     * @param array $page
     */
    private function debugger( $page = array())
    {
        if($page[1]==="css") {

            Debugger::getInstance()->stylesheet();
            die;
        }
        elseif($page[1]==="js") {

            Debugger::getInstance()->javascript();
            die;
        }
        else {

            die("no recognized debug file ".$page[1]."...");
        }
    }

    /**
     *
     *
     * A4 -> /[dom]/[ctrl]/[slug]
     * A3 -> /[dom]/[ctrl]/[slug]/[act]
     * A2 -> /[dom]/[ctrl]/[act]
     * A1 -> /[dom]/[ctrl]/
     *
     * B3 -> /[ctrl]/[slug]
     * B2 -> /[ctrl]/[slug]/[act]
     * B1 -> /[ctrl]/[act]
     *
     * C0 -> /[dom]
     * D0 -> /[ctrl]
     * E0 -> /[slug]
     *
     *
     */
    private function dispatch($page){

        if(isset($page[1]))     $ctr_path_a = '\App\Controller\\' . ucfirst($page[0]) . '\\' . ucfirst($page[1]) . 'Controller';
        if(isset($page[0]))     $ctr_path_b = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
        if(isset($page[0]))     $ctr_path_c = '\App\Controller\\' . ucfirst($page[0]) . '\DefaultController';
                                $ctr_path_d = '\App\Controller\DefaultController';

            if( isset($ctr_path_a) && class_exists($ctr_path_a))
            {
                Debugger::getInstance()->app("letter","a");
                Debugger::getInstance()->app("ctr_path",$ctr_path_a);
                $this->ctrl_name = $ctr_path_a ;
                $this->dom = $page[0] ?? null;
                $this->ctrl = $page[1] ?? "default";

                if( isset($page[3]) && method_exists($ctr_path_a, $page[3]))
                {
                    $this->slug = $page[2] ?? null;
                    $this->action = $page[3] ?? "index";
                }
            elseif( isset($page[2]) && method_exists($ctr_path_a, $page[2]))
                {
                    $this->slug = null;
                    $this->action = $page[2] ?? "index";
                }
            else
                {
                                $this->slug =  $page[2] ?? null;
                    $page[3] =  $this->action = "index";
                }

            }
        elseif( isset($ctr_path_b) && class_exists($ctr_path_b))
            {
                Debugger::getInstance()->app("letter","b");
                Debugger::getInstance()->app("ctr_path",$ctr_path_b);
                $this->ctrl_name = $ctr_path_b ;
                $this->dom = null ;
                $this->ctrl = $page[0] ?? "default";

                if( isset($page[2]) && method_exists($ctr_path_b, $page[2]))
                {
                    $this->slug = $page[1] ?? null;
                    $this->action = $page[2] ?? "index";
                }
            elseif( isset($page[1]) && method_exists($ctr_path_b, $page[1]))
                {
                    $this->slug = null;
                    $this->action = $page[1] ?? "index";
                }
            else
                {
                    $this->slug = $page[1] ?? null;
                    $this->action = $page[2] = "index";
                }

            }
        elseif( isset($ctr_path_c) && class_exists($ctr_path_c))
            {
                Debugger::getInstance()->app("letter","c");
                Debugger::getInstance()->app("ctr_path",$ctr_path_c);
                $this->ctrl_name = $ctr_path_c;
                $this->dom = $page[0] ?? null;
                $this->ctrl = "default";

                if(  isset($page[1]) && method_exists($ctr_path_c, $page[1]))
                {
                    $this->slug = null;
                    $this->action = $page[1] ?? "index";
                }
            else
                {
                    $this->slug = $page[1] ?? null;
                    $this->action = "index";
                }

            }
        elseif( isset($ctr_path_d) && class_exists($ctr_path_d))
            {
                Debugger::getInstance()->app("letter","d");
                Debugger::getInstance()->app("ctr_path",$ctr_path_d);
                $this->ctrl_name = $ctr_path_d;
                $this->dom = null;
                $this->ctrl = "default";
                $this->slug = $page[0] ?? null;
                $this->action = $page[1] = "index";
            }
        else
            {
                Debugger::getInstance()->app("letter","e");
                $this->ctrl_name = '\App\Controller\DefaultController';
                $this->dom = null ;
                $this->ctrl = "default";
                $this->slug = $page[0] ?? null;
               $this->action =  $page[1] = "index";
            }

        return $page ;
    }

    /**
     * @param string $slug
     * @return Request
     */
    public function setSlug(string $slug): Request
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return bool
     * valuable
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
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return mixed|null
     */
    public function getDom(): ?string
    {
        return $this->dom;
    }
}