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

        /**
         * module debug
         */
        if(DEBUG && $page[0]=="debug")
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
            $this->dispatch($page);

                /**

                if (count($page) == 1)
            {
                $this->ctrl_name = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
                $this->dom = null ;
                $this->ctrl = $page[0] ?? "default";
                $this->action = "index";
                $this->slug = null;
            }
            elseif (count($page) == 2) {
                $this->ctrl_name = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
                $this->dom = null ;
                $this->ctrl = $page[0] ?? "default";
                $this->action = $page[1] ?? "index";
                $this->slug = null;
            }
            elseif (count($page) == 3) {

                $ctr_path_a = '\App\Controller\\' . ucfirst($page[0]) . '\\' . ucfirst($page[1]) . 'Controller';
                $ctr_path_b = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';

                if( class_exists($ctr_path_a) )
                 {
                     $this->ctrl_name = $ctr_path_a ;
                     $this->dom = $page[0] ?? null ;
                     $this->ctrl = $page[1] ?? "default";

                     $this->slug = null;
                     $this->action = $page[2] ?? "index";
                 }
                elseif( class_exists($ctr_path_b) )
                {
                    $this->ctrl_name = $ctr_path_b ;
                    $this->dom = null ;
                    $this->ctrl = $page[0] ?? "default";


                    $this->slug = $page[1] ?? null;
                    $this->action = $page[2] ?? "index";
                }
            }
            elseif (count($page) == 4) {

                $ctr_path_a = '\App\Controller\\' . ucfirst($page[0]) . '\\' . ucfirst($page[1]) . 'Controller';
                //$ctr_path_b = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';

                if( class_exists($ctr_path_a) ) {
                    $this->ctrl_name = $ctr_path_a;
                    $this->dom = $page[0] ?? null;
                    $this->ctrl = $page[1] ?? "default";
                    $this->slug = $page[2] ?? null;
                    $this->action = $page[3] ?? "index";
                }
            }

                **/
        }

        $this->page = $page ;

        //var_dump($this);
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
                echo "a dispatch<br/>";
                $this->ctrl_name = $ctr_path_a ;
                $this->dom = $page[0] ?? null;
                $this->ctrl = $page[1] ?? "default";

                if( isset($page[3]) && method_exists($ctr_path_a, $page[3]))
                {
                    echo "call3<br/>";
                    $this->slug = $page[2] ?? null;
                    $this->action = $page[3] ?? "index";
                }
                elseif( isset($page[2]) && method_exists($ctr_path_a, $page[2]))
                {
                    echo "call2<br/>";
                    $this->slug = null;
                    $this->action = $page[2] ?? "index";
                }
                else
                {
                    echo "call1<br/>";
                    $this->slug =  $page[2] ?? null;
                    $this->action = "index";
                }
            }
        elseif( isset($ctr_path_b) && class_exists($ctr_path_b))
            {
                echo "b dispatch<br/>";
                $this->ctrl_name = $ctr_path_b ;
                $this->dom = null ;
                $this->ctrl = $page[0] ?? "default";

                if( isset($page[2]) && method_exists($ctr_path_a, $page[2]))
                {
                    echo "call2<br/>";
                    $this->slug = $page[1] ?? null;
                    $this->action = $page[2] ?? "index";
                }
                elseif(  isset($page[1]) && method_exists($ctr_path_a, $page[1]))
                {
                    echo "call1<br/>";
                    $this->slug = null;
                    $this->action = $page[1] ?? "index";
                }
                else
                {
                    echo "call0<br/>";
                    $this->slug =  $page[1] ?? null;
                    $this->action = "index";
                }
            }
        elseif( isset($ctr_path_c) && class_exists($ctr_path_c))
            {
                echo "c dispatch<br/>";
                $this->ctrl_name = $ctr_path_c;
                $this->dom = $page[0] ?? null;
                $this->ctrl = "default";
                $this->slug = null;
                $this->action = "index";
            }
        elseif( isset($ctr_path_d) && class_exists($ctr_path_d))
            {
                echo "d dispatch<br/>";
                $this->ctrl_name = $ctr_path_d;
                $this->dom = null;
                $this->ctrl = "default";
                $this->slug = $page[0] ?? null;
                $this->action = "index";
            }
        else
            {
                echo "default dispatch<br/>";
                $this->ctrl_name = '\App\Controller\DefaultController';
                $this->dom = null ;
                $this->ctrl = "default";
                $this->slug = $page[0] ?? null;
                $this->action = "index";
            }
    }

    private function verif_request($path,$page){

        if( isset($path) && class_exists($path))
        {
            echo "a dispatch<br/>";
            $this->ctrl_name = $path ;
            $this->dom = $page[0] ?? null;
            $this->ctrl = $page[1] ?? "default";

            if( isset($page[3]) && method_exists($path, $page[3]))
            {
                echo "call3<br/>";
                $this->slug = $page[2] ?? null;
                $this->action = $page[3] ?? "index";
            }
            elseif( isset($page[2]) && method_exists($path, $page[2]))
            {
                echo "call2<br/>";
                $this->slug = null;
                $this->action = $page[2] ?? "index";
            }
            elseif(  isset($page[1]) && method_exists($path, $page[1]))
            {
                echo "call1<br/>";
                $this->slug = null;
                $this->action = $page[1] ?? "index";
            }
            else
            {
                echo "call0<br/>";
                $this->slug = null;
                $this->action = "index";
            }
        }

        return false ;

    }

    private function reader($path,$ctrl="default",$action="index",$slug=null,$dom=null){

        echo "default dispatch<br/>";
        $this->ctrl_name    = $path;
        $this->dom          = $dom ;
        $this->ctrl         = $ctrl;
        $this->slug         = $slug;
        $this->action       = $action;

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