<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */

namespace App\Controller\Admin;

use App;
use Core\Auth\CryptAuth;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AppController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("User");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_article($post)
    {
        $form = new Form($post);

        $categories = array( "ressource","statistique");

        $form->input("titre", array('label' => "titre article"))
            ->select("categorie_id", array('options' => $categories, 'label' => "Categorie"),$categories)
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    public function index()
    {
        $this->posts = $this->User->all();

        Render::getInstance()->setView("Admin/User/home");
    }

    /**
     *
     */
    public function add(){

        if(Post::submited()) {

            if($this->User->create( Post::content('post'))){

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("edit")->setCtl("user")
                    ->send();
            }
        }

        $this->form = $this->form_article(Post::content('post'));

        Render::getInstance()->setView("Admin/User/single");
    }

    /**
     *
     */
    public function delete(){

        if(Post::submitted()) {

            if(Post::has('id')) {

                $post = $this->User->find(Post::get('id'));
                if (!$post) App::notFound();
            }

            if(Post::has('conf')) {

                if ($this->User->archive(Post::get('id'))) {

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("user")
                        ->send();


                }
            }
        }

        $this->categories = $this->Categorie->list('id','nom');

        Render::getInstance()->setView("Admin/User/delete");
    }

    /**
     *
     */
    public function single(){

        if(Post::submited("post")) {

            if($this->User->update(Get::getInstance()->val('id'), Post::content("post")))
            {
                FlashBuilder::create("item ajouté","success");

                Redirect::getInstance()->setParams(array("id" => Get::getInstance()->val('id') ))
                    ->setDom("admin")->setAct("single")->setCtl("user")
                    ->send();
            }
        }

        if(Get::getInstance()->has('id')) {

            $this->post = $this->User->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();
        }

        Header::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_article($this->post);

        Render::getInstance()->setView("Admin/User/single");
    }
}