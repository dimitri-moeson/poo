<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 20:32
 */

namespace App\Controller\Admin;

use App;
use App\View\Form\ArticleForm;
use Core\HTML\Env\Post;
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

        $this->form = ArticleForm::_categorie(Post::getInstance()->content());

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
    public function single($id){

        if(Post::getInstance()->submit($id)) {

            Post::getInstance()->val("type","categorie");

            if($this->ArticleService->record($id)){

                FlashBuilder::create("catégorie modifié","success");

                Redirect::getInstance()->setParams(array("id" => $id ))
                    ->setDom("admin")->setAct("edit")->setCtl("categorie")
                    ->send();
            }

        }

        if(!is_null($id)) {

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single cat");
        }
        $keywords = $this->Keyword->index($id);

        Header::getInstance()->setTitle($this->post->titre);

        $this->form = ArticleForm::_categorie($this->post,$keywords);

        Render::getInstance()->setView("Admin/Blog/Categorie/single");
    }
}