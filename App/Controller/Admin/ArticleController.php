<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */

namespace App\Controller\Admin;

use App;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

class ArticleController extends AppController
{
    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Article");
        $this->loadModel("Blog\Keyword");
        $this->loadModel("Blog\Indexion");

        $this->loadService("Article");

        $this->categories = $this->Article->allOf("categorie");
        $this->posts = $this->Article->allOf("article");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_article($post,$keywords = [])
    {
        $form = new Form($post);

        $categories = $this->Article->listing('categorie');

        $form->input("titre", array('label' => "titre article"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description/Extrait" ))
            ->select("parent_id", array('options' => $categories, 'label' => "Categorie"),$categories)
            ->input("date", array('type' => 'date', 'label' => "ajouté"))
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            //->input("type",array("type"=>"hidden", "value"=>"article"))
            ->submit("Enregistrer");

        return $form ;
    }

    public function index()
    {
        Render::getInstance()->setView("Admin/Blog/home"); // , compact('posts','categories'));
    }
    
    public function add(){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","article");

            if($this->ArticleService->record()){

                FlashBuilder::create("article créé","success");

                Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                    ->setAct("edit")->setCtl("article")->setDom("admin")
                    ->send();
            }
        }

        $this->form = $this->form_article(Post::getInstance()->content('post'));

        Render::getInstance()->setView("Admin/Blog/single"); // , compact('form','categories'));
    }
    
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id'))
            {
                $this->post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("delete post");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->archive(Post::getInstance()->val('id')))
                {
                    FlashBuilder::create("article supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("article")
                        ->send();
                }
            }
        }

        Render::getInstance()->setView("Admin/Blog/delete"); // , compact('posts','categories'));
    }
    
    public function single($id){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","article");

            if($this->ArticleService->record($id))
            {
                FlashBuilder::create("article modifié","success");
            }
            Redirect::getInstance()->setParams(array("id" => $id ))
                ->setAct("edit")->setCtl("article")->setDom("admin")
                ->send();
        }

        if(!is_null($id)){

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single post");

            $keywords = $this->Keyword->index($id);

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = $this->form_article($this->post,$keywords);
        }


        Render::getInstance()->setView("Admin/Blog/single"); // , compact('post','categories','success','form'));
    }
}