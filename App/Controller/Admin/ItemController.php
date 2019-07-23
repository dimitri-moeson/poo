<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */

namespace App\Controller\Admin;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\View\Form\ItemForm;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;

class ItemController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Item\Item");
    }

    private function form_article($post)
    {
        $form = new Form($post);

        $form->input("name", array('label' => "Nom"));
        $form->addInput("img", ItemForm::select_img(@$post->img) ) ;
        $form->addInput("type", ItemForm::select_typ(@$post->type) ) ;
        $form->addInput("objet", ItemForm::select_obj(@$post->objet) ) ;

        //$form->select("type", array('options' => ItemEntity::type_arr , 'label' => "type"), ItemEntity::type_arr );
        //$form->select("objet", array('options' => ItemEntity::categorie_arr , 'label' => "Categorie"), ItemEntity::categorie_arr );

        if(is_array($post) || !isset($post->id))
        {}

        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif", "class" => "editor"))
            ->input("vie", array('label' => "Moyenne"));


        /* if(is_object($post) && isset($post->id)){

         }*/

        $form->submit("Enregistrer");


        return $form ;
    }

    public function index()
    {
        if(Get::getInstance()->has('type'))
            $this->posts = $this->Item->typeListing([ Get::getInstance()->val('type') ]);
        else
            $this->posts = $this->Item->all();

        Render::getInstance()->setView("Admin/Item/home"); // , compact('posts','categories'));
    }
    
    public function add(){

        if(Post::getInstance()->submited()) {

            if($this->Item->create( Post::getInstance()->content('post'))){

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("single")->setCtl("item")
                    ->send();

            }
        }

        $this->posts = $this->Item->all();
        $this->form = $this->form_article(Post::getInstance()->content('post'));

        Render::getInstance()->setView("Admin/Item/single"); // , compact('form','categories'));
    }
    
    public function delete(){


        if(Post::getInstance()->submited()) {

            if(Post::getInstance()->has('id')) {

                $this->post = $this->Item->find(Post::getInstance()->val('id'));
                if (!$this->post) App::notFound();
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Item->delete(Post::getInstance()->val('id'))) {

                    $this->success = true ;

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("item")
                        ->send();

                }
            }
        }

        $this->posts = $this->Item->all();

        Render::getInstance()->setView("Admin/Item/delete"); // , compact('posts','categories'));
    }
    
    public function single(){

        if(Post::getInstance()->submited("post")) {

            if($this->Item->update( Get::getInstance()->val('id'), Post::getInstance()->content("post")))
            {
                //$this->success = true ;
                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("single")->setCtl("item")
                    ->send();
            }
        }

        if(Get::getInstance()->has('id')) {

            $this->post = $this->Item->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();
        }

        App::getInstance()->setTitle($this->post->titre);

        $this->posts = $this->Item->all();

        $this->form = $this->form_article($this->post);

        Render::getInstance()->setView("Admin/Item/single"); // , compact('post','categories','success','form'));
    }
}