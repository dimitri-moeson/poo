<?php

namespace App\Controller;

use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Render\Render;

class GuildController extends AppController
{
    /**
     * GuildController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Guild\Role");
        $this->loadModel("Game\Guild\Guild");
        $this->loadModel("Game\Guild\Droit");
        $this->loadModel("Game\Guild\Member");
        $this->loadModel("Game\Guild\Privilege");
        $this->loadModel("Game\Guild\Invitation");

        Render::getInstance()->setTemplate('guild');
    }

    /**
     *
     */
    public function creer()
    {
        if(Post::getInstance()->submit()){

            $this->Guild->create(array(
                "name" => Post::getInstance()->val("name")
            ));
        }

        $this->form = new Form();

        $this->form->input("name")->submit();
    }

    /**
     * le membre saisi le login dans la barre de recheche, si 1 ou plusieurs login corresponde
     * la guilde envoie une invitation
     */
    public function recruter($recrue = null )
    {
        if(Post::getInstance()->submit()){

            if(Post::getInstance()->has("invitation")){

                if(!is_null($recrue)){

                    $recrus = $this->User->find($recrue);

                    $this->Invitation->create(array(

                        "dest_id" => $recrus->id,
                        "guild_id" => $_SESSION['guild_id']

                    ));

                }
            }

            if(Post::getInstance()->has("search")){

                if(is_null($recrue)){

                    $this->recrus = $this->User->search(Post::getInstance()->val("recrue"));
                }
            }
        }
    }

    /**
     * le joeur accepte l'invitation et rejoind la guilde
     */
    public function rejoindre($recrus)
    {
        if(Post::getInstance()->submit()) {

            if (Post::getInstance()->has("accepte")) {

                $this->Member->create(array(

                    "dest_id" => $recrus,
                    "guild_id" => $_SESSION['guild_id']

                ));

            }
        }
    }

    /**
     * membre( guild - joueur )
     */
    public function membres()
    {
        $this->membres = $this->Member->list($_SESSION['guild_id']);
    }

    /**
     * role ( guild - role )
     */
    public function roles()
    {
        $this->roles = $this->Role->list($_SESSION['guild_id']);
    }

    /**
     * droit ( guild - role - droit )
     */
    public function droits()
    {
        $this->droits = $this->Droit->list($_SESSION['guild_id']);
    }

    /**
     * privileges ( guild - member - droit )
     */
    public function privileges()
    {
        $this->privileges = $this->Privilege->list($_SESSION['guild_id']);
    }
}