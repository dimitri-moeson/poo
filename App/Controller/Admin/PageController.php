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

class PageController extends AppController
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

        $this->posts = $this->Article->allOf("page");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_article($post,$keywords = [])
    {
        $form = new Form($post);

        $categories = $this->Article->listing('page');

        $form->input("titre", array('label' => "titre article"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description/Extrait" ))
            //->select("parent_id", array('options' => $categories, 'label' => "Page parente"),$categories)
            //->input("date", array('type' => 'date', 'label' => "ajouté"))
                ->choice("default",array( "type" => "radio"),array( 1 => "Oui", 0 => "Non"))
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->submit("Enregistrer");

        return $form ;
    }

    public function index()
    {
        Render::getInstance()->setView("Admin/Page/home"); // , compact('posts','categories'));
    }
    
    public function add(){

        if(Post::getInstance()->submited()) {

            Post::getInstance()->val("type","page");

            if($this->ArticleService->record()){

                FlashBuilder::create("page créé","success");

                Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                    ->setAct("single")->setCtl("page")->setDom("admin")
                    ->send();
            }
        }

        $this->form = $this->form_article(Post::getInstance()->content('post'));

        Render::getInstance()->setView("Admin/Page/single"); // , compact('form','categories'));
    }
    
    public function delete(){

        if(Post::getInstance()->submited()) {

            if(Post::getInstance()->has('id'))
            {
                $this->post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$this->post) App::notFound();
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->archive(Post::getInstance()->val('id')))
                {
                    FlashBuilder::create("Page supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("page")
                        ->send();
                }
            }
        }

        Render::getInstance()->setView("Admin/Page/delete"); // , compact('posts','categories'));
    }
    
    public function single(){

        if(Post::getInstance()->submited()) {

            Post::getInstance()->val("type","page");

            if($this->ArticleService->record(Get::getInstance()->val('id')))
            {
                FlashBuilder::create("Page modifié","success");
            }
            Redirect::getInstance()->setParams(array("id" => Get::getInstance()->val('id') ))
                ->setAct("single")->setCtl("page")->setDom("admin")
                ->send();
        }

        if(Get::getInstance()->has('id')){

            $this->post = $this->Article->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();

            $keywords = $this->Keyword->index(Get::getInstance()->val('id'));

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = $this->form_article($this->post,$keywords);
        }


        Render::getInstance()->setView("Admin/Page/single"); // , compact('post','categories','success','form'));
    }
}