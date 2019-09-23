<?php
namespace Core\HTML\Header;

use DateTime;

class Header
{
    /**
     * @var Header
     */
    private static $_instance;

    /**
     * @var string
     */
    private $title = "Test";
    private $keywords = "Test";
    private $description = "Test";

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Header();
        }

        return self::$_instance;
    }

    /**
     * App constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title . " | " . $this->title;
    }

    /**
     * @param $action
     */
    public function add_js($action){

        $this->js[] = $action ;

    }

    /**
     * @return string
     */
    public function call_js(){

        $script = "";

        foreach ( $this->js as $action   ){

            /** @lang html script-$action.js => ?js='.$action.' */
            $script .='<script src="/js/'.$action.'" type="application/x-javascript"></script>'  ;
        }

        return $script ;
    }

    /**
     * @param $action
     */
    public function add_css($action){

        $this->css[] = $action ;

    }

    /**
     * @return string
     */
    public function call_css(){

        $script = "";

        foreach ( $this->css as $action   ){

            /** @lang html style-$action.css => ?css='.$action.' */
            $script .=  '<link rel="stylesheet" href="/css/'.$action.'" crossorigin="anonymous" type="text/css" media="screen" />'  ;
        }

        return $script ;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getCreated(): DateTime
    {
        return new DateTime();
    }

    public function getUpdated(): DateTime
    {
        return new DateTime();
    }
}