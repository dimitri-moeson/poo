<?php


namespace Core\Controller;


use Core\Config;

class Stylesheet
{
    /**
     * readfile
     */
    public function __construct($path){

        $page = explode('.', $path );

        $this->path = Config::VIEW_DIR.'/Assets/Styles/'.implode('/', array_map("ucfirst", $page)).'.css';

    }

    /**
     *
     */
    public function read()
    {
        header('content-type: text/css');

        if(file_exists($this->path))
        {
            readfile($this->path);
        }
        /*else
        {
            echo " src js['" . $this->path . "'] inacessible ...";
        }*/

        die;

    }
}