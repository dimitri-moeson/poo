<?php


namespace App\Model\Service;


use App;
use App\Model\Entity\UserEntity;
use App\Model\Service;
use Core\Auth\CryptAuth;
use Core\Auth\DatabaseAuth;
use Core\Auth\UserAuth;
use Core\Database\QueryBuilder;
use Core\HTML\Env\Post;
use Core\Session\FlashBuilder;
use Exception;

/**
 * Class UserService
 * @package App\Model\Service
 */
class UserService extends Service
{
    /**
     * @var DatabaseAuth
     */
    private static $auth ;

    /**
     * @var CryptAuth
     */
    private static $cryptor ;

    /**
     * @var
     */
    private $user;

    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        try{

            parent::__construct();

            $this->loadModel("User");
            $this->loadModel("Game\Personnage\Personnage");

            self::$auth = new DatabaseAuth(App::getInstance()->getDb());
            self::$cryptor = CryptAuth::getInstance( self::$auth->getEncryptionKey());

            if(self::$auth->logged()){

                $statement = QueryBuilder::init()->select('*')->from('user')->where("id = :id");

                $this->user = $this->UserBase->request($statement, array("id" => self::$auth->getUser('id')),true, UserEntity::class);

            }

        } catch(Exception $e){

            throw $e ;

        }
    }

    public function save($user,$step = "login" , $id = null ){

        if($step == "login") {

            if(trim($user['login'])!=='')
            {
                if (filter_var($user['mail'], FILTER_VALIDATE_EMAIL))
                {
                    if ($this->UserBase->exists_login($user['login']) == false)
                    {
                        if ($this->UserBase->exists_mail($user['mail']) == false)
                        {
                            if ($user['pswd'] === $user['pswd_conf'])
                            {
                                $this->UserBase->create(array(
                                    "login" => $user['login'],
                                    "mail" => $user['mail'],
                                    "pswd" => self::$cryptor->encrypt($user['pswd'])
                                ));

                                $_SESSION['inscription']['user_id'] = App::getInstance()->getDb()->lasInsertId();

                                $this->PersonnageBase->create(array(
                                    "user_id" => $_SESSION['inscription']['user_id'],
                                ));

                                $_SESSION['inscription']['perso_id'] = App::getInstance()->getDb()->lasInsertId();


                                return true;
                            }
                            else {
                                FlashBuilder::create("err conf...","alert");
                            }
                        }
                        else {
                            FlashBuilder::create("already mail...","alert");
                        }
                    }
                    else {
                        FlashBuilder::create("already login...","alert");
                    }
                }
                else {
                    FlashBuilder::create("invalid mail...","alert");
                }
            }
            else {
                FlashBuilder::create("empty login...","alert");
            }
        }
        elseif(!is_null($id)){

            /**$test1 = ( isset($_SESSION['inscription']['user_id'])  && $id == $_SESSION['inscription']['user_id']) ;**/
            $test2 = ( isset($_SESSION['inscription']['perso_id']) && $id == $_SESSION['inscription']['perso_id']) ;

            //var_dump($user);

                if($test2 && $step == "faction")
                {
                    $this->PersonnageBase->update(  $id ,array(
                        "user_id" => $id,
                        "faction_id" => $user['faction'],
                    ));

                    return true;
                }
            elseif($test2 && $step == "classe")
                {
                    $this->PersonnageBase->update(  $id , array(
                        "type" => $user['classe']
                    ));
                    return true;
                }
            elseif($test2 && $step == "race")
                {
                    $this->PersonnageBase->update(  $id , array(
                        "race_id" => $user['race']
                    ));
                    return true;
                }
            elseif($test2 && $step == "sexe")
                {
                    $this->PersonnageBase->update(  $id , array(
                        "sexe" => $user['sexe']
                    ));
                    return true;
                }
            elseif($test2 && $step == "personnage")
                {
                    $this->PersonnageBase->update(  $id , array(
                        "name" => $user['nom'],
                         "description" => $user['description']
                    ));
                    return true;
                }
            else
            {
                FlashBuilder::create("error step...","alert");
            }

        }
        else FlashBuilder::create("error id...","alert");


        return false;
    }


    /**
     * @param $old
     * @param $new
     * @param $rep
     */
    public function pswd($old,$new,$rep)
    {
        if($new === $rep)
        {
            if (self::$cryptor->decrypt($this->player->pswd) === $old ) {

                $this->UserBase->update( $this->user->id , array(

                    "pswd" => self::$cryptor->encrypt( $new )
                ) );
            }
        }
    }

    /**
     * @param $pswd
     * @param $new_mail
     * @param $rep_mail
     */
    public function email($pswd ,$new_mail ,$rep_mail ){

        if($new_mail ===$rep_mail)
        {
            if (filter_var($new_mail, FILTER_VALIDATE_EMAIL)) {

                if (self::$cryptor->decrypt($this->user->pswd) === $pswd) {

                    $this->UserBase->update( $this->user->id , array(

                        "mail" => $new_mail

                    ) );
                }

            }
        }
    }

    /**
     * find user current login ...
     * @param $login
     * @param $pswd
     * @return bool
     */
    public function login($login,$pswd)
    {
        $statement = QueryBuilder::init()->select('*')->from('user')->where("login = :login");

        $this->user = $this->UserBase->request($statement, array("login" => $login),true, UserEntity::class);

        if ($this->user) {

            if (self::$cryptor->decrypt($this->user->pswd) === $pswd) {

                return true ;
            }
        }

        unset($this->user);
        return false;

    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}