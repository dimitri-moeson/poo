<?php
namespace Core\HTML\Header;

use DateTime;

class Header
{
    /**
     * @var Header
     */
    private static $_instance;

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

    public function add_js($action){

        $this->js[] = $action ;

    }

    public function call_js(){

        $script = "";

        foreach ( $this->js as $action   ){

            $script .='<script src="?js='.$action.'" type="application/x-javascript"></script>'  ;
        }

        return $script ;
    }

    public function add_css($action){

        $this->css[] = $action ;

    }

    public function call_css(){

        $script = "";

        foreach ( $this->css as $action   ){

            $script .= /** @lang html */ '<link rel="stylesheet" href="?css='.$action.'" crossorigin="anonymous" type="text/css" media="screen" />'  ;
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