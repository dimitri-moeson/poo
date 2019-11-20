<?php


namespace App\Controller\Community;


use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\View\Form\InscriptionForm;
use Core\Auth\DatabaseAuth;
use Core\Render\Render;
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
        Render::getInstance()->setView("Community/Inscription/default");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());

        $this->loadModel("User");
        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Personnage\Personnage");

        $this->loadService("User");
        $this->loadService("Personnage");
    }

    /**
     * @brief renseigne identifiants de compte
     */
    public function login()
    {
        InscriptionForm::submit($this->UserService,"login","faction");

        $this->form = InscriptionForm::login($this->player, $this->auth);
    }

    /**
     * @brief selection de la faction du personnage
     *
     */
    public function faction()
    {
        InscriptionForm::submit($this->UserService,"faction","classe");

        $factions = $this->Item->typeListing(["faction"]); //listFaction();

        $this->form = InscriptionForm::faction($factions);
    }

    /**
     * @brief selection de la classe du personnage
     */
    public function classe()
    {
        InscriptionForm::submit($this->UserService,"classe","race");

        $classes = $this->Item->typeListing(ItemEntity::$type_arr["classe"]); //listClasse();

        $this->form = InscriptionForm::faction($classes);
    }

    /**
     * @brief selection de la race du personnage
     */
    public function race()
    {
        InscriptionForm::submit($this->UserService,"race","sexe");

        $races = $this->Item->typeListing(["race"]); //listClasse();

        $this->form = InscriptionForm::race($races);
    }

    /**
     * @brief selection du sexe du personnage
     */
    public function sexe()
    {
        InscriptionForm::submit($this->UserService,"sexe","personnage");

        $this->form = InscriptionForm::sexe();
    }

    /**
     * @brief description du personnage
     */
    public function personnage()
    {
        InscriptionForm::submit($this->UserService,"personnage","save");

        $this->form = InscriptionForm::personnage($this->legolas);
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