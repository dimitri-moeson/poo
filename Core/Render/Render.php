<?php


namespace Core\Render;


use Core\Config;
use Core\Controller\Controller;
use Core\Debugger\Debugger;
use Core\HTML\Template\Template;
use Core\Request\Request;
use Exception;

class Render
{
    /**
     * @var Render
     */
    private static $instance;
    /**
     * @var
     */
    protected $viewPath;

    /**
     * @var
     */
    protected $template;

    /**
     * @var
     */
    protected $view;

    /**
     * @param array $page
     */
    public function template($page= array() ) // , $variables = array())
    {
        if (is_null($this->view)) {
            $page = array_map('ucfirst', $page);
            $this->view = implode(DIRECTORY_SEPARATOR, $page);
        }
        $view_filename = $this->viewPath . "/" . $this->view . ".php";
        $template_filename = $this->viewPath . "/Templates/" . $this->template . ".php";

        $template = new Template($this->viewPath . "/Templates/");
    }

    /**
     * @param array $page
     * @return Render
     */
    public static function getInstance(Array $page = array())
    {
        if(is_null(self::$instance)){
            self::$instance = new self($page);
        }

        if(!empty($page))
            self::$instance->initView($page);

        return self::$instance;
    }

    /**
     * @param $view_filename
     * @param array $datas
     * @return false|string
     */
    public function block($view_filename , Array $datas = array() )
    {
        $view_file = Config::VIEW_DIR. "/Block/" . $view_filename . ".php";

        if(file_exists($view_file))
        {
            ob_start();
            extract($datas);

            require $view_file;

            return ob_get_clean();
        }
        else
        {
            echo $view_file;
        }
    }

    /**
     * @param array $page
     */
    public function initView(Array $page = array()){

        if (is_null($this->view)) {
            $page = array_map('ucfirst', $page);
            $this->view = implode(DIRECTORY_SEPARATOR, $page);
        }

    }

    /**
     * @param $_view
     */
    private function __construct(Array $page = array()  ) // , $variables = array())
    {
        if(!empty($page))
            $this->initView($page);
    }

    public function exec(Controller $controller)
    {
        $view_filename = $this->viewPath . "/" . $this->view . ".php";
        $template_filename = Config::VIEW_DIR. "/Templates/" . $this->template . ".php";

        $content = "chargement échoué ??";

        try
        {
            if(file_exists($view_filename))
            {
                ob_start();

                extract((array)$controller);
                require_once $view_filename;
                $content = ob_get_clean();
            }
            else{

                $controller->notFound("- view => ".$this->view." : ".$view_filename);
            }
        }
        catch (Exception $e)
        {
            var_dump($e);
            throw $e ;
        }

        require_once $template_filename ;
    }

    /**
     * @return mixed
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * @param mixed $viewPath
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
        Debugger::getInstance()->app('viewPath',$viewPath);
        return $this ;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        Debugger::getInstance()->app('template',$template);
        return $this;
    }

    /**
     * @param mixed $view
     * @return Controller
     */
    public function setView($view)
    {
        $this->view = $view;
        Debugger::getInstance()->app('view',$view);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }
}