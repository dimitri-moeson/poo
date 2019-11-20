<?php


namespace App\Controller\Admin;


use App;
use App\View\Form\SqlForm;
use Core\HTML\Env\Post;
use Core\Render\Render;

class SqlController extends AppController
{
    /**
     * SqlController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function dump(){

        echo "<pre>".App::getInstance()->getDb()->dump()."</pre>";

        die();

    }

    /**
     *
     */
    public function save(){

        header( "Content-Type: application/x-sql" );
        header( 'Content-Disposition: attachment; filename="' . date("Y-m-d_H:i:s").'.sql' . '"' );

        echo App::getInstance()->getDb()->dump();

        exit(0);

    }

    public function generate() {

        App::getInstance()->getDb()->generate();
    }

    /**
     *
     */
    public function index()
    {
        if(Post::getInstance()->submit())
        {
            $this->result = App::getInstance()->getDb()->query(Post::getInstance()->val("request"));
        }

        $this->form = SqlForm::_sql(Post::getInstance()->content());

        Render::getInstance()->setView("Admin/sql"); // , compact('posts','categories'));
    }
}