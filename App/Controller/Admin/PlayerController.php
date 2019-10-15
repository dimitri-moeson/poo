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
 * Class PlayerController
 * @package App\Controller\Admin
 */
class PlayerController extends AppController
{
    /**
     * PlayerController constructor.
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

        Render::getInstance()->setView("Admin/Player/home");
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

        Render::getInstance()->setView("Admin/Player/single");
    }

    /**
     * fonction archivage/suppression
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $this->post = $this->User->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("del user");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->User->archive(Post::getInstance()->val('id'))) {

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("user")
                        ->send();
                }
            }
        }

        $this->posts = $this->User->all();

        Render::getInstance()->setView("Admin/Player/delete");
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

        Render::getInstance()->setView("Admin/Player/single");
    }
}