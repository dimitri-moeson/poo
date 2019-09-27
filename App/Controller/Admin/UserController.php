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
    private function form_user($post)
    {
        $form = new Form($post);

        $form->input("login")
            ->input("mail")

            ->input("pswd", array(
                'readonly' => 'readonly' ,
                'disabled' => 'disabled',
                'value' => CryptAuth::getInstance($this->auth->getEncryptionKey())->decrypt($post->pswd)
            ))
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

        if(Post::getInstance()->submit()) {

            if($this->User->create( Post::content('post'))){

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("edit")->setCtl("user")
                    ->send();
            }
        }

        $this->form = $this->form_user(Post::content('post'));

        Render::getInstance()->setView("Admin/User/single");
    }

    /**
     *
     */
    public function delete(){

        if(Post::submitted()) {

            if(Post::has('id')) {

                $post = $this->User->find(Post::get('id'));
                if (!$post) $this->notFound("del user");
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
     * @param $id
     */
    public function single($id){

        if(Post::getInstance()->submit()) {

            if($this->User->update($id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("user édité","success");

                Redirect::getInstance()->setParams(array("id" => $id ))
                    ->setDom("admin")->setAct("single")->setCtl("user")
                    ->send();
            }
        }

        if(!is_null($id)) {

            $this->post = $this->User->find($id);
            if (!$this->post) $this->notFound("single user");
        }

        Header::getInstance()->setTitle($this->post->login);

        $this->form = $this->form_user($this->post);

        Render::getInstance()->setView("Admin/User/single");
    }
}