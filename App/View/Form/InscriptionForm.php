<?php


namespace App\View\Form;


use App\Model\Entity\UserEntity;
use App\Model\Service\UserService;
use Core\Auth\CryptAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class InscriptionForm
{
    /**
     * @param $index
     * @return bool
     */
    private static function sess_has($index){

        if( isset($_SESSION['inscription'][$index]))
        {
            $val = $_SESSION['inscription'][$index] ;

            $null  = is_null($val) ? 1 : 0 ;
            $empty =   empty($val) ? 1 : 0 ;

            if($null == 0 && $empty == 0 )
            {
                return true ;
            }
        }
    }

    /**
     * @param $index
     * @return mixed
     */
    private static function sess_val($index)
    {
        if( isset($_SESSION['inscription'][$index]))
        {
            $val = $_SESSION['inscription'][$index] ;

            $null  =   is_null($val) ? 1 : 0 ;
            $empty =     empty($val) ? 1 : 0 ;

            if($null == 0 && $empty == 0 )
            {
                return $val ;
            }
        }
    }

    /**
     * @param $page
     * @param $next
     */
    public static function submit (UserService $UserService, $page, $next)
    {
        if (Post::getInstance()->submit())
        {
            if( $page == "login")
            {
                $save = $UserService->save(Post::getInstance()->content(), $page);
            }
            else if (self::sess_has('perso_id'))
            {
                $save = $UserService->save(Post::getInstance()->content(), $page, self::sess_val('perso_id'));
            }
            else
            {
                FlashBuilder::create("creation du compte éronnée. Veuillez reprendre.", "alert");
                Redirect::getInstance()->setAct("login")->setCtl("inscription")->setDom("community")->send();
            }

            if ($save)
            {
                Redirect::getInstance()->setAct($next)->setCtl("inscription")->setDom("community")->send();
            }

        }
    }

    /**
     * @param UserEntity $player
     * @param $auth
     * @return Form
     */
    public static function login( $auth ){

        $form = new Form(Post::getInstance()->content() );

        $pswd = Post::getInstance()->val('pswd');

        $form
            ->input("login")
            ->input("mail",array("label" => "Email","name"=>"mail"))
            ->pswd("pswd", array(
                "conf" => true,
                "label" => "Mot de passe",
                'value' => $pswd
            ))
            ->submit("suivant")
        ;

        return $form ;
    }

    /**
     * @param $factions
     * @return Form
     */
    public static function faction($factions)
    {
        $form = new Form($_POST);

        foreach ($factions as $x => $faction) {
            $form->input("faction_" . $x, array(

                "type" => "submit",
                "name" => "faction",
                "label" => "<i class='" . $faction->img . "'/></i><br/>" . $faction->name,
                "value" => $faction->id
            ));
        }

        return $form ;
    }

    /**
     * @param $classes
     * @return Form
     */
    public static function classe($classes)
    {
        $form = new Form($_POST);

        foreach ($classes as $x => $classe) {
            $form->input("classe_" . $x, array(

                "type" => "submit",
                "name" => "classe",
                "label" => "<i class='" . $classe->img . "'/></i><br/>" . $classe->name,
                "value" => $classe->id
            ));
        }

        return $form ;
    }

    /**
     * @param $races
     * @return Form
     */
    public static function race($races)
    {
        $form = new Form();

        foreach ($races as $x => $race) {
            $form->input("race_" . $x, array(

                "type" => "submit",
                "name" => "race",
                "label" => "<i class='" . $race->img . "'/></i><br/>" . $race->name,
                "value" => $race->id
            ));
        }

        return $form ;
    }

    /**
     * @return Form
     */
    public static function sexe()
    {
        $form = new Form($_POST);

        $form
            ->input("sexe_0", array("name" => "sexe", "type" => "submit", "label" => "Homme", "value" => 1))
            ->input("sexe_1", array("name" => "sexe", "type" => "submit", "label" => "Femme", "value" => 2));

        return $form ;
    }

    /**
     * @param $legolas
     * @return Form
     */
    public static function personnage($legolas)
    {
        $form = new Form($legolas ?? $_POST);
        $form
            ->input("nom", array("name" => "nom", "type" => "text", "label" => "Nom du personnage"))
            ->input("description", array("name" => "description", "type" => "textarea", "label" => "Histoire"))
            ->submit("Terminer");

        return $form ;
    }
}