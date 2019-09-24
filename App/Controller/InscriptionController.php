<?php


namespace App\Controller;


use App\Controller\AppController;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\Session;
use Exception;

/**
 * Class InscriptionController
 * @package App\Controller
 */
class InscriptionController extends AppController
{
    /**
     * InscriptionController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        //Render::getInstance()->setTemplate('default');
        Render::getInstance()->setView("Inscription/default");

        $this->loadModel("User");
        $this->loadModel("Game\Item\Item");

        $this->loadService("User");
    }

    /**
     *
     */
    public function login()
    {
        if(Post::getInstance()->submit())
        {
            if($this->UserService->save(Post::getInstance()->content(),"login"))
            {
                if(isset($_SESSION['inscription']['user_id']))
                {
                    Redirect::getInstance()->setAct("faction")->send();
                }
            }
        }

        $this->form = new Form();

        $this->form
            ->input("login")
            ->input("email")
            ->pswd("pswd_new", array("conf" => true, "label" => "Mot de passe" ))
            ->submit("suivant")
        ;
    }

    /**
     * @brief selection de la faction du personnage
     *
     */
    public function faction()
    {
        if(Post::getInstance()->submit())
        {
            if (isset($_SESSION['inscription']['user_id']))
            {
                if ($this->UserService->save(Post::getInstance()->content(), "faction", $_SESSION['inscription']['user_id']))
                {
                    Redirect::getInstance()->setAct("classe")->send();
                }
            }
        }

        $this->factions = $this->Item->typeListing(array("faction")) ; //listFaction();

        $this->form = new Form();

        //print_r($this->factions);

        //$choices = array();

        foreach ( $this->factions as $x =>  $faction)
        {
            //$choices[$faction->id] = $faction->name;

            $this->form->input("faction_".$x, array(

                "type" => "submit",
                "label" => $faction->name,
                "value" => $faction->id
            ));
        }

        //$this->form->choice("faction", array("type" => "checkbox"),$choices);
    }

    /**
     * @brief selection de la classe du personnage
     */
    public function classe()
    {
        if(Post::getInstance()->submit())
        {
            if (isset($_SESSION['inscription']['perso_id']))
            {
                if ($this->UserService->save(Post::getInstance()->content(), "classe",$_SESSION['inscription']['perso_id']))
                {
                    Redirect::getInstance()->setAct("race")->send();
                }
            }
        }

        $this->classes = $this->Item->typeListing(array("classe")) ; //listClasse();

        $this->form = new Form();

        foreach ( $this->classes as $classe)
        {
            $this->form->input("classe_".$classe->id);
        }
    }

    /**
     * @brief selection de la race du personnage
     */
    public function race()
    {
        if(Post::getInstance()->submit())
        {
            if (isset($_SESSION['inscription']['perso_id']))
            {
                if ($this->UserService->save(Post::getInstance()->content(), "race",$_SESSION['inscription']['perso_id']))
                {
                    Redirect::getInstance()->setAct("sexe")->send();
                }
            }
        }
    }

    /**
     * @brief selection du sexe du personnage
     */
    public function sexe()
    {
        if(Post::getInstance()->submit())
        {
            if (isset($_SESSION['inscription']['perso_id']))
            {
                if ($this->UserService->save(Post::getInstance()->content(), "sexe", $_SESSION['inscription']['perso_id']))
                {
                    Redirect::getInstance()->setAct("personnage")->send();
                }
            }
        }

    }

    /**
     * @brief description du personnage
     */
    public function personnage()
    {
        if(Post::getInstance()->submit())
        {
            if (isset($_SESSION['inscription']['perso_id']))
            {
                if ($this->UserService->save(Post::getInstance()->content(), "personnage", $_SESSION['inscription']['perso_id']))
                {
                    Redirect::getInstance()->setAct("save")->send();
                }
            }
        }

    }

    /**
     *
     */
    public function save()
    {

    }

    /**
     *
     */
    public function confirm()
    {

    }

}