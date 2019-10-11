<?php


namespace App\Controller\Admin;


use App;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
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
     * @param $post
     * @return Form
     */
    private function form_sql($post)
    {
        $form = new Form($post);

        $form->textarea("request", array(

            'name' => "request" ,
            "label" => "Requête" ,
            "data-show-icon" => 'true',
            "data-content" => '<i class="glyphicon glyphicon-console"></i> Requête',

        ))
            ->submit("Executer");

        return $form ;
    }

    public function dump(){

        echo nl2br(App::getInstance()->getDb()->dump());

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

        $this->form = $this->form_sql(Post::getInstance()->content());

        Render::getInstance()->setView("Admin/sql"); // , compact('posts','categories'));
    }
}