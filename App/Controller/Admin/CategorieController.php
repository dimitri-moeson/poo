<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 20:32
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

class CategorieController extends AppController
{
    /**
     * CategorieController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Article");
        $this->loadModel("Blog\Keyword");

        $this->categories =  $this->Article->allOf("categorie");
        $this->loadService("Article");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_categorie($post,$keywords)
    {
        $form = new Form($post);

        $form->input("titre", array('label' => "Titre Cat"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description" ))
            //->input("type",array("type"=>"hidden" ,"value" =>"categorie"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    public function index()
    {
        $this->posts = $this->Article->allOf("article");

        Render::getInstance()->setView("Admin/Blog/Categorie/home");
    }

    /**
     *
     */
    public function add(){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","categorie");

            if($this->ArticleService->record()){

                FlashBuilder::create("catégorie créé","success");

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("edit")->setCtl("categorie")
                    ->send();
            }

        }

        $this->form = $this->form_categorie(Post::getInstance()->content());

        Render::getInstance()->setView("Admin/Blog/Categorie/single");
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$post) $this->notFound("del cat");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->archive(Post::getInstance()->val('id'))) {

                    FlashBuilder::create("catégorie supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("categorie")
                        ->send();
                }
            }

        }

        Render::getInstance()->setView("Admin/Blog/Categorie/delete");
    }

    /**
     *
     */
    public function single(){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","categorie");

            if($this->ArticleService->record(Get::getInstance()->val('id'))){

                FlashBuilder::create("catégorie modifié","success");

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("edit")->setCtl("categorie")
                    ->send();
            }

        }

        if(Get::getInstance()->has('id')) {

            $this->post = $this->Article->find(Get::getInstance()->val('id'));
            if (!$this->post) $this->notFound("single cat");
        }
        $keywords = $this->Keyword->index(Get::getInstance()->val('id'));

        Header::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_categorie($this->post,$keywords);

        Render::getInstance()->setView("Admin/Blog/Categorie/single");
    }
}