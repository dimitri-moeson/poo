<?php


namespace App\Controller;


use App;
use App\Controller\AppController;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Service\PersonnageService;
use Core\Auth\CryptAuth;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;
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

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());

        $this->loadModel("User");
        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Personnage\Personnage");

        $this->loadService("User");
        $this->loadService("Personnage");
    }

    /**
     * @param bool $redirect
     * @return bool
     */
    private function verif($redirect = true ){

        if (!isset($_SESSION['inscription']['user_id']) && $redirect) {
            FlashBuilder::create("creation du compte éronnée. Veuillez reprendre.","alert");
            Redirect::getInstance()->setAct("login")->send();
        }

        if (isset($_SESSION['inscription']['user_id'])) {

            $this->player = $this->User->find( $_SESSION['inscription']['user_id']  );

            if (isset($_SESSION['inscription']['perso_id'])) {
                if ($this->PersonnageService instanceof PersonnageService) {
                    $this->legolas = $this->PersonnageService->recup($_SESSION['inscription']['perso_id']);
                }
            }

            if (!$this->legolas) $this->notFound("personnage");
        }

        return true ;
    }

    /**
     * @brief renseigne identifiants de compte
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

        $this->verif(false);

        $this->form = new Form($this->player ?? $_POST);

        $this->form
            ->input("login")
            ->input("mail",array("label" => "Email"))
            ->pswd("pswd", array(
                "conf" => true,
                "label" => "Mot de passe",
                'value' => ( CryptAuth::getInstance($this->auth->getEncryptionKey())->decrypt($this->player->pswd) ?? $_POST['pswd'] )
            ))
            ->submit("suivant")
        ;
    }

    /**
     * @brief selection de la faction du personnage
     *
     */
    public function faction()
    {
        if($this->verif()) {
            if (Post::getInstance()->submit()) {
                if (isset($_SESSION['inscription']['perso_id'])) {
                    if ($this->UserService->save(Post::getInstance()->content(), "faction", $_SESSION['inscription']['perso_id'])) {
                        Redirect::getInstance()->setAct("classe")->send();
                    }
                }
            }

            $this->factions = $this->Item->typeListing(array("faction")); //listFaction();

            $this->form = new Form($_POST);

            foreach ($this->factions as $x => $faction) {
                $this->form->input("faction_" . $x, array(

                    "type" => "submit",
                    "name" => "faction",
                    "label" => "<i class='" . $faction->img . "'/></i><br/>" . $faction->name,
                    "value" => $faction->id
                ));
            }
        }
    }

    /**
     * @brief selection de la classe du personnage
     */
    public function classe()
    {
        if($this->verif()) {
            if (Post::getInstance()->submit()) {
                if (isset($_SESSION['inscription']['perso_id'])) {
                    if ($this->UserService->save(Post::getInstance()->content(), "classe", $_SESSION['inscription']['perso_id'])) {
                        Redirect::getInstance()->setAct("race")->send();
                    }
                }
            }

            $this->classes = $this->Item->typeListing(ItemEntity::type_arr["classe"]); //listClasse();

            $this->form = new Form($_POST);

            foreach ($this->classes as $x => $classe) {
                $this->form->input("classe_" . $x, array(

                    "type" => "submit",
                    "name" => "classe",
                    "label" => "<i class='" . $classe->img . "'/></i><br/>" . $classe->name,
                    "value" => $classe->id
                ));
            }
        }
    }

    /**
     * @brief selection de la race du personnage
     */
    public function race()
    {
        if($this->verif()) {
            if (Post::getInstance()->submit()) {
                if (isset($_SESSION['inscription']['perso_id'])) {
                    if ($this->UserService->save(Post::getInstance()->content(), "race", $_SESSION['inscription']['perso_id'])) {
                        Redirect::getInstance()->setAct("sexe")->send();
                    }
                }
            }

            $this->races = $this->Item->typeListing(array("race")); //listClasse();

            $this->form = new Form();

            foreach ($this->races as $x => $race) {
                $this->form->input("race_" . $x, array(

                    "type" => "submit",
                    "name" => "race",
                    "label" => "<i class='" . $race->img . "'/></i><br/>" . $race->name,
                    "value" => $race->id
                ));
            }
        }
    }

    /**
     * @brief selection du sexe du personnage
     */
    public function sexe()
    {
        if($this->verif()) {
            if (Post::getInstance()->submit()) {
                if (isset($_SESSION['inscription']['perso_id'])) {
                    if ($this->UserService->save(Post::getInstance()->content(), "sexe", $_SESSION['inscription']['perso_id'])) {
                        Redirect::getInstance()->setAct("personnage")->send();
                    }
                }
            }

            $this->form = new Form($_POST);

            $this->form->input("sexe_0", array("name" => "sexe", "type" => "submit", "label" => "Homme", "value" => 1));
            $this->form->input("sexe_1", array("name" => "sexe", "type" => "submit", "label" => "Femme", "value" => 2));
        }
    }

    /**
     * @brief description du personnage
     */
    public function personnage()
    {
        if($this->verif()) {
            if (Post::getInstance()->submit()) {
                if (isset($_SESSION['inscription']['perso_id'])) {
                    if ($this->UserService->save(Post::getInstance()->content(), "personnage", $_SESSION['inscription']['perso_id'])) {
                        FlashBuilder::create("creation du compte terminée. Vou pouvez vous connecter.", "success");
                        Redirect::getInstance()->setAct("save")->send();
                    }
                }
            }
            if (isset($_SESSION['inscription']['user_id'])) {
                $this->form = new Form($this->legolas ?? $_POST);
                $this->form
                    ->input("nom", array("name" => "nom", "type" => "text", "label" => "Nom du personnage"))
                    ->input("description", array("name" => "description", "type" => "textarea", "label" => "Histoire"))
                    ->submit("Terminer");
            }
        }
    }

    /**
     *
     */
    public function save()
    {
        if($this->verif()) {

            unset($_SESSION['inscription']);
            $this->form = null;
            $this->auth->login($this->player);
        }
    }

    /**
     *
     */
    public function confirm()
    {

    }

}