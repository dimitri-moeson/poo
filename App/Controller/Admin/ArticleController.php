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
use Core\Redirect\Redirect;
use Core\Render\Render;

class ArticleController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Categorie");
        $this->loadModel("Blog\Article");
    }

    private function form_article($post)
    {
        $form = new Form($post);

        $categories = $this->Categorie->list('id','nom');

        $form->input("titre", array('label' => "titre article"))
            ->select("categorie_id", array('options' => $categories, 'label' => "Categorie"),$categories)
            ->input("date", array('type' => 'date', 'label' => "ajouté"))
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            ->submit("Enregistrer");

        return $form ;
    }

    public function index()
    {
        $this->posts = $this->Article->all();
        $this->categories = $this->Categorie->all();

        Render::getInstance()->setView("Admin/Blog/home"); // , compact('posts','categories'));
    }
    
    public function add(){

        if(Post::submited()) {

            if($this->Article->create( Post::content('post'))){

                Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                    ->setAct("edit")->setCtl("article")->setDom("admin")
                    ->send();
            }
        }

        $this->form = $this->form_article(Post::content('post'));
        $this->categories = $this->Categorie->list('id','nom');
        $this->posts = $this->Article->all();

        Render::getInstance()->setView("Admin/Blog/single"); // , compact('form','categories'));
    }
    
    public function delete(){

        if(Post::getInstance()->submited()) {

            if(Post::getInstance()->has('id'))
            {
                $this->post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$this->post) App::notFound();
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->delete(Post::getInstance()->val('id')))
                {
                    Render::getInstance()->redirect("index");
                }
            }
        }

        $this->posts = $this->Categorie->all();

        Render::getInstance()->setView("Admin/Blog/delete"); // , compact('posts','categories'));
    }
    
    public function single(){

        if(Post::submited("post")) {

            if($this->Article->update(Get::getInstance()->val('id'), Post::content("post")))
            {
                $this->success = true ;
                Redirect::reload();

            }
        }

        if(Get::getInstance()->has('id')){

            $this->post = $this->Article->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();
        }

        $this->categories = $this->Categorie->list('id','nom');
        $this->posts = $this->Article->all();

        App::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_article($this->post, $this->categories);

        Render::getInstance()->setView("Admin/Blog/single"); // , compact('post','categories','success','form'));
    }
}