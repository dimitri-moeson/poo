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