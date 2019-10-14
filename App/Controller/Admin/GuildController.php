<?php
/**
 * Created by PhpStorm.
 * GuildEntity: admin
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
 * Class GuildController
 * @package App\Controller\Admin
 */
class GuildController extends AppController
{
    /**
     * GuildController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Guild\Guild");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_Guild($post)
    {
        $form = new Form($post);

        $form
            ->input("name")

            ->textarea("presente",array('type' => 'textarea', 'label' => "Presentation", "class" => "editor"))

            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    public function index()
    {
        $this->posts = $this->Guild->all();

        Render::getInstance()->setView("Admin/Guild/home");
    }

    /**
     * fonction archivage/suppression
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $this->post = $this->Guild->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("del Guild");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Guild->archive(Post::getInstance()->val('id'))) {

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("Guild")
                        ->send();
                }
            }
        }

        $this->posts = $this->Guild->all();

        Render::getInstance()->setView("Admin/Guild/delete");
    }

    /**
     * @param $id
     */
    public function single($id){

        if(Post::getInstance()->submit()) {

            if($this->Guild->update($id, Post::getInstance()->content("post")))
            {
                FlashBuilder::create("Guild édité","success");

                Redirect::getInstance()->setParams(array("id" => $id ))
                    ->setDom("admin")->setAct("single")->setCtl("Guild")
                    ->send();
            }
        }

        if(!is_null($id)) {

            $this->post = $this->Guild->find($id);
            if (!$this->post) $this->notFound("single Guild");
        }

        Header::getInstance()->setTitle($this->post->login);

        $this->form = $this->form_Guild($this->post);

        Render::getInstance()->setView("Admin/Guild/single");
    }
}