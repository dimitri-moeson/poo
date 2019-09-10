<?php


namespace Core\Controller;


use Core\Config;

class Javascript
{
    /**
     * readfile
     */
    public function __construct($path){

        $page = explode('.', $path );


        $this->path = Config::VIEW_DIR.'/Assets/Scripts/'.implode('/', array_map("ucfirst", $page)).'.js';

    }

    /**
     *
     */
    public function read()
    {
        header('Content-type: application/javascript');

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