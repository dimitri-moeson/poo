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
        $form->addInput("objet", ItemForm::select_obj("objet",@$post->objet) ) ;

        $form->input("name", array('label' => "Nom"));
        $form->input("vie", array('label' => "Moyenne"));

        $form->submit("Enregistrer");


        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_descript($post)
    {
        $form = new Form($post);
        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif", "class" => "editor"));

        $form->submit("Enregistrer");


        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_icon($post){

        $form = new Form($post);
        $form->addInput("img", ItemForm::checkbox_img(@$post->img) ) ;

        $form->submit("Enregistrer");

        return $form ;

    }

    private function form_inventaire($post, $link = null){

        $form =new Form($link);

        if(isset($link->id))
            $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"edition"))
                ->input("id", array("type"=>"hidden","name"=>"id","value"=>@$link->id));
        else
            $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"ajout"));

        $form->input("parent_id", array("type"=>"hidden","name"=>"parent_id","value"=>@$link->parent_id))
            ->input("rubrique", array("type"=>"hidden","name"=>"rubrique","value"=>@$post->type));

        return $form ;
    }

    /**
     * @param $post
     * @param null $link
     * @return Form
     */
    private function form_mission($post, $link = null, $items){

        $form = $this->form_inventaire($post, $link);

        $form->input("val", array("name"=>"val","label"=>"quantité"));

        $form->select("child_id",array("name"=>"child_id","value"=>@$link->child_id,"label"=>"cible"),$items);

       $form->addInput("type", ItemForm::select_obj("type",@$link->type,"mission") );

       /** elseif( in_array($post->type , ItemEntity::type_arr["classe"]) ) { }
        elseif( in_array($post->type , ItemEntity::type_arr["faune"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["arme_1_main"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["equipement"]) ) { }
        elseif( in_array($post->type , ItemEntity::type_arr["arme_2_main"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["batiment"]) ) { }**/
        $form->submit("reg");

        return $form ;
    }

    private function form_attribut($post, $link = null , $items )
    {
        $form = $this->form_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"statistique"));
        $form->input("val", array("name"=>"val","label"=>"score"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "caractéristique"), $items);

        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg");

        return $form;
    }

    private function form_ressource($post, $link = null , $items )
    {
        $form = $this->form_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"ressource"));
        $form->input("val", array("name"=>"val","label"=>"score"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "ressource"), $items);

        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg");

        return $form;
    }

    /**
     * @param $post
     * @param null $link
     * @param $items
     * @return Form
     */
    private function form_craft($post, $link = null,$items )
    {

        $form = $this->form_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"composant"));
        $form->input("val", array("name"=>"val","label"=>"quantité"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "composant"), $items);

        $form->submit("reg");

        return $form;
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
    private function getItemSluged($id){

        if(!is_null($id)) {

            $this->post = $this->Item->find($id);
            if (!$this->post) $this->notFound("single item");

            $type = $this->post->type;

            if(!is_null($type)) {
                $this->posts = $this->Item->typeListing([$type]);
                $this->type = $type;
                Request::getInstance()->setSlug($type);
            }else {
                $this->posts = $this->Item->all();
                $this->type = null ;
            }
        }
    }

    /**
     * @param $id
     */
    public function single($id){

        $this->getItemSluged($id);

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg($id)
                    ->setDom("admin")->setAct("single")->setCtl("item")
                    ->send();
            }
        }



        Header::getInstance()->setTitle($this->post->titre);



        $this->form = $this->form_article($this->post);

        Render::getInstance()->setView("Admin/Item/single"); // , compact('post','categories','success','form'));
    }

    /**
     * @param $id
     */
    public function descript($id){

        $this->getItemSluged($id);

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg($id)
                    ->setDom("admin")->setAct("descript")->setCtl("item")
                    ->send();
            }
        }

        Header::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_descript($this->post);

        Render::getInstance()->setView("Admin/Item/descript"); // , compact('post','categories','success','form'));

    }

    /**
     * @param $id
     */
    public function icone($id){

        $this->getItemSluged($id);

        if(Post::getInstance()->submit()) {

            if($this->Item->update( $id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("item modifié","success");

                Redirect::getInstance()->setSlg($id)
                    ->setDom("admin")->setAct("icone")->setCtl("item")
                    ->send();
            }
        }

        Header::getInstance()->setTitle($this->post->titre);


        $this->form = $this->form_icon($this->post);

        Render::getInstance()->setView("Admin/Item/icone"); // , compact('post','categories','success','form'));

    }

    /**
     * @param $id
     * @return bool
     */
    private function submit_inventaire($id){

        if(Post::getInstance()->submit()) {

            $datas = array(
                "parent_id" =>$id ,
                "rubrique" => Post::getInstance()->val("rubrique"),
                "child_id" => Post::getInstance()->val("child_id"),
                "type" => Post::getInstance()->val("type"),
                "val" => Post::getInstance()->val("val"),
            );

            if(Post::getInstance()->has("id")) {

                $_id = Post::getInstance()->has("id");

                if ($this->Inventaire->update($_id, $datas)) {
                    FlashBuilder::create("cible modifié", "success");

                }
            }
            else
            {
                if ($this->Inventaire->create($datas)) {
                    FlashBuilder::create("cible ajouté", "success");
                }
            }

            return true ;
        }
    }

    /**
     * @param $id
     * @param $categorie
     */
    private function init_forms_inventaire($categorie, $linked , $items ){

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer $categorie",
                "form" => $this->form_craft($this->post, $link,$items)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter $categorie",
            "form" => $this->form_craft($this->post,null,$items)
        );

    }

    /**
     * if type => in "aventure"
     * @param $id
     */
    public function mission($id)
    {
        $this->getItemSluged($id);

        if($this->submit_inventaire($id)){

            Redirect::getInstance()->setSlg($id)
                ->setDom("admin")->setAct("mission")->setCtl("item")
                ->send();
        }

        Header::getInstance()->setTitle($this->post->titre);

        $linked = $this->Inventaire->findBy(array("parent_id" => $id));
        $items =  $this->Item->list("id","name");


        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer caractéristique",
                "form" => $this->form_mission($this->post, $link,$items)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter caractéristique",
            "form" => $this->form_mission($this->post,null,$items)
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }

    /**
     * @param $id
     */
    public function attribut($id)
    {
        $this->getItemSluged($id);

        if($this->submit_inventaire($id)){

            Redirect::getInstance()->setSlg($id)
                ->setDom("admin")->setAct("attribut")->setCtl("item")
                ->send();
        }

        Header::getInstance()->setTitle($this->post->titre);

        $linked = $this->Inventaire->findBy(array("parent_id" => $id,"type"=>"statistique","rubrique"=>$this->post->type));
        $items = $this->Item->list("id", "name",["type"=>"statistique"]);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer statistique",
                "form" => $this->form_attribut($this->post, $link,$items)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter statistique",
            "form" => $this->form_attribut($this->post, null, $items)
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }

    public function ressource($id)
    {
        $this->getItemSluged($id);

        if($this->submit_inventaire($id)){

            Redirect::getInstance()->setSlg($id)
                ->setDom("admin")->setAct("ressource")->setCtl("item")
                ->send();
        }

        Header::getInstance()->setTitle($this->post->titre);

        $linked = $this->Inventaire->findBy(array("parent_id" => $id,"type"=>"ressource","rubrique"=>$this->post->type));
        $items = $this->Item->list("id", "name",["type"=>"ressource"]);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer ressource",
                "form" => $this->form_ressource($this->post, $link,$items)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter ressource",
            "form" => $this->form_ressource($this->post, null, $items)
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }

    /**
     * @param $id
     */
    public function craft($id)
    {
        $this->getItemSluged($id);

        if($this->submit_inventaire($id)){

            Redirect::getInstance()->setSlg($id)
                ->setDom("admin")->setAct("craft")->setCtl("item")
                ->send();
        }

        Header::getInstance()->setTitle($this->post->titre);

        $linked = $this->Inventaire->findBy(array("parent_id" => $id,"type"=>"composant","rubrique"=>$this->post->type));
        $items = $this->Item->list("id", "name",["type"=>"composant"]);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer composant",
                "form" => $this->form_craft($this->post, $link,$items)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter composant",
            "form" => $this->form_craft($this->post,null,$items)
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }
}