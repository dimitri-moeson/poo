<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 20:32
 */

namespace App\Controller\Admin;

use App;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;

class CategorieController extends AppController
{
    /**
     * CategorieController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Categorie");
        $this->loadModel("Blog\Article");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_categorie($post)
    {
        $form = new Form($post);

        $form->input("nom", array('label' => "Titre Cat"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    public function index()
    {
        $this->posts = $this->Article->all();
        $this->categories = $this->Categorie->all();

        Render::getInstance()->setView("Admin/Blog/Categorie/home");
    }

    /**
     *
     */
    public function add(){

        if(Post::getInstance()->submited()) {

            if($this->Categorie->create( Post::getInstance()->content())){

                header("location:?p=admin.categorie.edit&id=".App::getInstance()->getDb()->lasInsertId());

                Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId() ))
                    ->setDom("admin")->setAct("edit")->setCtl("article")
                    ->send();
            }

        }

        $this->categories = $this->Categorie->all();

        $this->form = $this->form_categorie(Post::getInstance()->content());

        Render::getInstance()->setView("Admin/Blog/Categorie/single");
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submited()) {

            if(Post::getInstance()->has('id')) {

                $post = $this->Categorie->find(Post::getInstance()->val('id'));
                if (!$post) App::notFound();
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Categorie->delete(Post::getInstance()->val('id'))) {

                    header("location: admin.php");

                }
            }

        }

        $this->categories = $this->Categorie->all();

        Render::getInstance()->setView("Admin/Blog/Categorie/delete");
    }

    /**
     *
     */
    public function single(){

        if(Post::getInstance()->submited()) {

            if($this->Categorie->update(Get::getInstance()->val('id'), Post::getInstance()->content())){

                $this->success = true ;

            }

        }

        if(Get::getInstance()->has('id')) {

            $this->post = $this->Categorie->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();
        }

        $this->categories = $this->Categorie->all();

        App::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_categorie($this->post);

        Render::getInstance()->setView("Admin/Blog/Categorie/single");
    }
}