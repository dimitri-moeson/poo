<?php


namespace App\Controller\Admin;

use App;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Render\Render;

class PersonnageController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Personnage\Personnage");
    }

    private function form_perso($post)
    {
        $form = new Form($post);

        $form->input("titre", array('name' => "Nom"))
            ->choice("sexe", array('name' => "genre"),array( 1 => "homme" , 2 => "femme"))
            ->submit("Enregistrer");

        return $form ;
    }

    public function index()
    {
        $this->personnages = $this->Personnage->all();
    }

    public function delete(){

        if(Post::getInstance()->submited()) {

            if(Post::getInstance()->has('id')) {

                $post = $this->Personnage->find(Post::getInstance()->val('id'));
                if (!$post) App::notFound();
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Personnage->delete(Post::getInstance()->val('id'))) {

                    header("location: admin.php");

                }
            }

        }
    }

    public function single()
    {
        if(Post::getInstance()->submited()) {

            if($this->Personnage->update(Get::getInstance()->val('id'), Post::getInstance()->content())){

                $this->success = true ;

            }

        }

        if(Get::getInstance()->has('id')) {

            $this->post = $this->Personnage->find(Get::getInstance()->val('id'));
            if (!$this->post) App::notFound();
        }

        $this->categories = $this->Personnage->list('id','nom');

        App::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_categorie($this->post);
    }
}