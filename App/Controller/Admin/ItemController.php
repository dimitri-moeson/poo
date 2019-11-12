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

        $this->form = ItemForm::_article(Post::getInstance()->content('post'));

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



        $this->form = ItemForm::_article($this->post);

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

        $this->form = ItemForm::_descript($this->post);

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


        $this->form = ItemForm::_icon($this->post);

        Render::getInstance()->setView("Admin/Item/icone"); // , compact('post','categories','success','form'));

    }

    /**
     * @param $id
     * @return bool
     */
    private function submit_inventaire($id){

        if(Post::getInstance()->submit()) {

            $datas = array(
                "parent_id" => $id ,
                "rubrique" => Post::getInstance()->val("rubrique"),
                "child_id" => Post::getInstance()->val("child_id"),
                "type" => Post::getInstance()->val("type"),
                "val" => Post::getInstance()->val("val"),
                "caract" => Post::getInstance()->val("caract")
            );

            $edit = false ;

            if(Post::getInstance()->has("id"))
            {
                if(Post::getInstance()->has("action"))
                {
                    if(Post::getInstance()->val("action")=="edition")
                    {
                        $edit = true ;
                    }
                    if(Post::getInstance()->val("action")=="ajout")
                    {
                        $edit = true ;
                    }
                }
            }

            if($edit)
            {
                $_id = intval(Post::getInstance()->val("id"));

                if ($this->Inventaire->update($_id, $datas))
                    FlashBuilder::create("cible modifié", "success");
            }
            else
            {
                if ($this->Inventaire->create($datas))
                    FlashBuilder::create("cible ajouté", "success");
            }
            // die("submit_inventaire return");
            return true ;
        }
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
        $items =  $this->Item->typeListing(array('strum',"composant")) ; //request($statementT);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer caractéristique",
                "form" => ItemForm::_mission($this->post, $link,$items,$x)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter caractéristique",
            "form" => ItemForm::_mission($this->post,null,$items, @($x++))
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
        //$items = $this->Item->list("id", "name",["type"=>"statistique"]);
        $items =  $this->Item->typeListing(array('statistique')) ; //request($statementT);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer statistique",
                "form" => ItemForm::_attribut($this->post, $link,$items,$x)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter statistique",
            "form" => ItemForm::_attribut($this->post, null, $items, @($x++))
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }

    /**
     * @param $id
     */
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
        //$items = $this->Item->list("id", "name",["type"=>"ressource"]);
        $items =  $this->Item->typeListing(array('ressource')) ; //request($statementT);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer ressource",
                "form" => ItemForm::_ressource($this->post, $link,$items,$x)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter ressource",
            "form" => ItemForm::_ressource($this->post, null, $items, @($x++))
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
        //$items = $this->Item->list("id", "name",["type"=>"composant"]);
        $items =  $this->Item->typeListing(array('composant')) ; //request($statementT);

        foreach($linked as $x => $link) {

            $this->forms[$x] = array(

                "label" => "Editer composant",
                "form" => ItemForm::_craft($this->post, $link,$items,$x)
            );
        }

        $this->forms[] = array(

            "label" => "Ajouter composant",
            "form" => ItemForm::_craft($this->post,null,$items, @($x++))
        );

        Render::getInstance()->setView("Admin/Item/mission");
    }
}