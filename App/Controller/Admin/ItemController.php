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
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Request\Request;
use Core\Session\FlashBuilder;

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

        $form->addInput("img", ItemForm::select_img(@$post->img) ) ;
        $form->addInput("type", ItemForm::select_typ(@$post->type ?? $post["type"]) ) ;
        $form->addInput("objet", ItemForm::select_obj(@$post->objet) ) ;

        $form->input("name", array('label' => "Nom"));
        $form->input("vie", array('label' => "Moyenne"));

        //$form->select("type", array('options' => ItemEntity::type_arr , 'label' => "type"), ItemEntity::type_arr );
        //$form->select("objet", array('options' => ItemEntity::categorie_arr , 'label' => "Categorie"), ItemEntity::categorie_arr );

        if(is_array($post) || !isset($post->id))
        {}

        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif", "class" => "editor"));


        /** if(is_object($post) && isset($post->id)){

         }*/

        $form->submit("Enregistrer");


        return $form ;
    }

    public function index($type = null )
    {
        if(!is_null($type)) {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
        }else {
            $this->posts = $this->Item->all();
            $this->type = null ;
        }
        Render::getInstance()->setView("Admin/Item/home"); // , compact('posts','categories'));
    }
    
    public function add($type = null ){

        if(Post::getInstance()->submit()) {

            if($this->Item->create( Post::getInstance()->content('post'))){

                FlashBuilder::create("item ajouté","success");

                Redirect::getInstance()->setSlg(App::getInstance()->getDb()->lasInsertId() )
                    ->setDom("admin")->setAct("single")->setCtl("item")
                    ->send();
            }
        }

        if(!is_null($type)) {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
            Post::getInstance()->val('type',$type);
        }else {
            $this->posts = $this->Item->all();
            $this->type = null ;
        }

        $this->form = $this->form_article(Post::getInstance()->content('post'));

        Render::getInstance()->setView("Admin/Item/single"); // , compact('form','categories'));
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $this->post = $this->Item->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("del itm");

                $type = $this->post->type;
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Item->archive(Post::getInstance()->val('id'))) {

                    $this->success = true ;

                    FlashBuilder::create("item supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("item")
                        ->send();

                }
            }
        }

        if(!is_null($type)) {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
        }else {
            $this->posts = $this->Item->all();
            $this->type = null ;
        }

        Render::getInstance()->setView("Admin/Item/delete"); // , compact('posts','categories'));
    }

    /**
     * @param $id
     */
    public function single($id){

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg(App::getInstance()->getDb()->lasInsertId())
                    ->setDom("admin")->setAct("single")->setCtl("item")
                    ->send();
            }
        }

        if(!is_null($id)) {

            $this->post = $this->Item->find($id);
            if (!$this->post) $this->notFound("single item");

            $type = $this->post->type;
        }

        Header::getInstance()->setTitle($this->post->titre);

        if(!is_null($type)) {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
            Request::getInstance()->setSlug($type);
        }else {
            $this->posts = $this->Item->all();
            $this->type = null ;
        }

        $this->form = $this->form_article($this->post);

        Render::getInstance()->setView("Admin/Item/single"); // , compact('post','categories','success','form'));
    }
}