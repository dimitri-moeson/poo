<?php


namespace App\Controller;


use Core\HTML\Env\Post;
use Core\Render\Render;

class GuildController extends AppController
{
    /**
     * GuildController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        Render::getInstance()->setTemplate('guild');
    }

    /**
     *
     */
    public function creer()
    {
        if(Post::getInstance()->submit()){

        }
    }

    /**
     *
     */
    public function recruter()
    {
        
    }

    /**
     *
     */
    public function rejoindre()
    {
        
    }

    /**
     *
     */
    public function membres()
    {
        
    }

    /**
     *
     */
    public function droits()
    {
        
    }
}