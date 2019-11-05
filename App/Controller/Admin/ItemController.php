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
    /**
     * ItemController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Inventaire\Inventaire");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_article($post)
    {
        $form = new Form($post);

        $form->addInput("type", ItemForm::select_typ(@$post->type ?? $post["type"]) ) ;
        $form->addInput("objet", ItemForm::select_obj(@$post->objet) ) ;

        $form->input("name", array('label' => "Nom"));
        $form->input("vie", array('label' => "Moyenne"));

        $form->submit("Enregistrer");


        return $form ;
    }

    private function form_descript($post)
    {
        $form = new Form($post);
        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif", "class" => "editor"));

        $form->submit("Enregistrer");


        return $form ;
    }

    private function form_icon($post){

        $form = new Form($post);
        $form->addInput("img", ItemForm::checkbox_img(@$post->img) ) ;

        $form->submit("Enregistrer");

        return $form ;

    }

    private function form_mission($post, $link = null ){

        $form =new Form($link);

        if(isset($link->id))
            $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"edition"))
                ->input("id", array("type"=>"hidden","name"=>"id","value"=>@$link->id));
        else
            $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"ajout"));

        $form->input("parent_id", array("type"=>"hidden","name"=>"parent_id","value"=>@$link->parent_id))
            ->input("rubrique", array("type"=>"hidden","name"=>"rubrique","value"=>@$post->type))
            ->input("val")
            ->select("child_id",array("name"=>"child_id","value"=>@$link->child_id),[])
            ->addInput("type", ItemForm::select_obj(@$link->type,"mission") )
            ->submit("reg")
        ;

        return $form ;
    }
    /**
     * @param null $type
     */
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

    /**
     * @param null $type
     */
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

                Redirect::getInstance()->setSlg($id)
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

    /**
     * @param $id
     */
    public function descript($id){

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg($id)
                    ->setDom("admin")->setAct("descript")->setCtl("item")
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

        $this->form = $this->form_descript($this->post);

        Render::getInstance()->setView("Admin/Item/descript"); // , compact('post','categories','success','form'));

    }

    /**
     * @param $id
     */
    public function icone($id){

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg($id)
                    ->setDom("admin")->setAct("icone")->setCtl("item")
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

        $this->form = $this->form_icon($this->post);

        Render::getInstance()->setView("Admin/Item/icone"); // , compact('post','categories','success','form'));

    }

    /**
     * if type => in "aventure"
     * @param $id
     */
    public function mission($id)
    {
        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has("id")) {

                $_id = Post::getInstance()->has("id");

                if ($this->Inventaire->update($_id, Post::getInstance()->content("post"))) {
                    FlashBuilder::create("cible modifié", "success");

                }
            }
            else
            {
                if ($this->Inventaire->create(Post::getInstance()->content("post"))) {
                    FlashBuilder::create("cible ajouté", "success");


                }
            }
            Redirect::getInstance()->setSlg($id)
                ->setDom("admin")->setAct("mission")->setCtl("item")
                ->send();
        }
        if(!is_null($id)) {

            $this->post = $this->Item->find($id);
            if (!$this->post) $this->notFound("single item");

            $type = $this->post->type;
            $this->linked = $this->Inventaire->findBy(array("parent_id" => $id));
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

        foreach($this->linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer Mission",
                "form" => $this->form_mission($this->post, $link)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter Mission",
            "form" => $this->form_mission($this->post)
        );


        Render::getInstance()->setView("Admin/Item/mission"); // , compact('post','categories','success','form'));
    }
}