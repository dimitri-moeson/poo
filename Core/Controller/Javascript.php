<?php


namespace Core\Controller;


class Javascript
{
    /**
     * readfile
     */
    public function __construct($path){

        $page = explode('.', $path );


        $this->path = '/App/View/Assets/Scripts/'.implode('/', array_map("ucfirst", $page)).'.js';

    }

    /**
     *
     */
    public function read()
    {
        header('Content-type: application/javascript');

        //echo " // " .ROOT."/".$this->path."\n\r" ;

        if(file_exists(ROOT.$this->path))
        {
            readfile(ROOT . $this->path);
        }
        else
        {
            echo " src js['" . $this->path . "'] inacessible ...";
        }

        die;

    }
}