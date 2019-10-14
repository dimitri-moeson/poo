<?php

namespace App\Controller;

use App;
use Core\Auth\DatabaseAuth;
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
        $this->loadModel("Game\Guild\Acces");
        $this->loadModel("Game\Guild\Member");
        $this->loadModel("Game\Guild\Invitation");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
        $this->has = $this->Member->existsBy(array("member_id" => $this->auth->getUser('id')));

        if($this->has) {

            $this->appart = $this->Member->findOneBy(array("member_id" => $this->auth->getUser('id')));

            $this->guild_center = $this->Guild->find($this->appart->guild_id);

        }

        $this->loadService("User");
        $this->loadService("Guild");
    }

    /**
     * liste des guildes existant ou page d'accueil de la guilde d'adhésion
     */
    public function index()
    {
        if($this->has)
        {
            Render::getInstance()->setView("Guild/Visit");
        }

        else
        {
            $this->guild_list = $this->Guild->All();

            Render::getInstance()->setView("Guild/Welcome");
        }

    }

    /**
     * @param null $guilde_id
     */
    public function visiter($guilde_id = null)
    {
        if(!is_null($guilde_id))
        {
            $this->guild_center = $this->Guild->find($guilde_id);

            Render::getInstance()->setView("Guild/Visit");

        }

        //$this->notFound("no guild ref.");

    }

    /**
     * créer un nouvelle guilde.
     */
    public function creer()
    {
        if(!$this->has)
        {
            if (Post::getInstance()->submit())
            {
                $this->GuildService->creer(Post::getInstance()->val("name"), $this->auth->getUser('id'));
            }
            $this->form = new Form();

            $this->form->input("name")->submit("envoie");
        }
        else
        {
            $this->forbidden("already have guild");
        }
    }

    /**
     *
     */
    public function manager()
    {
        if($this->has)
        {
            if ($this->GuildService->isManager($this->guild_center->id))
            {
                if (Post::getInstance()->submit())
                {
                    $this->GuildService->update($this->guild_center->id, Post::getInstance()->content());
                }
                $this->form = new Form();

                $this->form->input("name")
                    ->textarea("presente",array('type' => 'textarea', 'label' => "Presentation", "class" => "editor"))
                    ->submit("envoie");

                Render::getInstance()->setView("Guild/Creer");

            } else {
                $this->forbidden("not manager");
            }
        }
        else {
            $this->forbidden("not member");
        }
    }

    /**
     * le membre saisi le login dans la barre de recheche, si 1 ou plusieurs login corresponde
     * la guilde envoie une invitation
     * @param null $recrue
     */
    public function recruter($recrue = null )
    {
        if(Post::getInstance()->submit()){

            if(Post::getInstance()->has("invitation")){

                if(!is_null($recrue)){

                    $recrus = $this->User->find($recrue);
                    $guild = $this->Member->findOneBy(array("member_id" => $this->auth->getUser('id')));

                    $this->GuildService->inviter($guild->id,$recrus->id);

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
     * le joueur accepte l'invitation et rejoind la guilde
     *
     * @param $recrus
     */
    public function rejoindre($recrus)
    {
        if(Post::getInstance()->submit()) {

            if (Post::getInstance()->has("accepte")) {

                $this->GuildService->rejoindre($_SESSION['guild_id'],$recrus);

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
        $this->droits = $this->Acces->list($_SESSION['guild_id'],'droit');
    }

    /**
     * privileges ( guild - member - droit )
     */
    public function privileges()
    {
        $this->privileges = $this->Acces->list($_SESSION['guild_id'],'privilege');
    }
}