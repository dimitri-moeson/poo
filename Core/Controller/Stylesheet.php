<?php


namespace Core\Controller;


class Stylesheet
{
    /**
     * readfile
     */
    public function __construct($path){

        $page = explode('.', $path );


        $this->path = '/App/View/Assets/Styles/'.implode('/', array_map("ucfirst", $page)).'.css';

    }

    /**
     *
     */
    public function read()
    {
        header('content-type: text/css');

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