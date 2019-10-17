<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */

namespace App\Controller\Admin;

use App;
use App\Model\Service\ArticleService;
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

        $this->posts = $this->Article->findBy(array("type"=>"page"),array("position" => "desc"));
        //allOf("page",null,"position");
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
            ->choice("default",array( "type" => "radio"),array( 1 => "Oui", 0 => "Non"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->submit("Enregistrer");

        return $form ;
    }

    private function form_content($post)
    {
        $form = new Form($post);

        $form
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    public function index()
    {
        Render::getInstance()->setView("Admin/Page/home"); // , compact('posts','categories'));

        $this->max = count($this->posts);
    }

    /**
     *
     */
    public function add(){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","page");

            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->record()) {

                    FlashBuilder::create("page créé", "success");

                    Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId()))
                        ->setAct("single")->setCtl("page")->setDom("admin")
                        ->send();
                }
            }
        }

        $this->form = $this->form_article(Post::getInstance()->content('post'));

        Render::getInstance()->setView("Admin/Page/single"); // , compact('form','categories'));
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->archive(Post::getInstance()->val('id')))
                {
                    FlashBuilder::create("Page supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("page")
                        ->send();
                }
            }

            if(Post::getInstance()->has('id'))
            {
                $this->post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("del page");
            }
        }

        Render::getInstance()->setView("Admin/Page/delete"); // , compact('posts','categories'));
    }

    /**
     * @param $id
     */
    public function single($id){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","page");

            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->record($id)) {
                    FlashBuilder::create("Page modifié", "success");
                }
            }
            Redirect::getInstance()//->setParams(array("id" => $id ))
                ->setAct("single")->setCtl("page")->setDom("admin")->setSlg($id )
                ->send();
        }

        if(!is_null($id)){

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single page");

            $keywords = $this->Keyword->index($id);

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = $this->form_article($this->post,$keywords);
        }

        Render::getInstance()->setView("Admin/Page/single"); // , compact('post','categories','success','form'));
    }

    public function content($id){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","page");

            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->updateContent($id)) {
                    FlashBuilder::create("page modifié", "success");
                }
                Redirect::getInstance()//->setParams(array("id" => $id))
                    ->setAct("edit")->setCtl("page")->setDom("admin")->setSlg($id )
                    ->send();
            }
        }

        if(!is_null($id)){

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single post");

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = $this->form_content($this->post);
        }


        Render::getInstance()->setView("Admin/Page/content"); // , compact('post','categories','success','form'));
    }


    /**
     *
     */
    public function position(){

        if(Post::getInstance()->has("pos-mov")){

            list($id,$sens) = explode("|",Post::getInstance()->val("pos-mov"));

            if($this->ArticleService instanceof ArticleService)
                $this->ArticleService->setPosition($id,$sens, "page");

            Redirect::getInstance()->setDom("admin")->setAct("index")->send();
        }
    }
}