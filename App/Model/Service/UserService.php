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

class UserService extends Service
{
    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        try{

            parent::__construct();

            $this->loadModel("User");

            $this->loadService("Map");
            $this->loadService("Inventaire");
            $this->loadService("Item");

            $auth = new DatabaseAuth(App::getInstance()->getDb());

            if($auth->logged()){

                $statement = QueryBuilder::init()->select('*')->from('user')->where("id = :id");

                $this->user = $this->db->prepare($statement, array("id" => $auth->getUser('id')), UserEntity::class, true);

            }

        } catch(\Exception $e){

            throw $e ;

        }
    }

    public function pswd($old,$new,$rep)
    {
        if($new === $rep)
        {
            if ($this->cryptor->decrypt($this->player->pswd) === $old ) {

                $this->User->update( $this->user->id , array(

                    "pswd" => $this->cryptor->encrypt( $new )
                ) );
            }
        }
    }

    public function email($pswd ,$new_mail ,$rep_mail ){

        if($new_mail ===$rep_mail)
        {
            if (filter_var($new_mail, FILTER_VALIDATE_EMAIL)) {

                if ($this->cryptor->decrypt($this->player->pswd) === $pswd) {

                    $this->User->update( $this->user->id , array(

                        "mail" => $new_mail

                    ) );
                }

            }
        }
    }

    public function login($login,$pswd)
    {
        $statement = QueryBuilder::init()->select('*')->from('user')->where("login = :login");

        $this->user = $this->db->prepare($statement, array("login" => $login), UserEntity::class, true);

        if ($this->user) {

            $cryptor = CryptAuth::getInstance($this->encryption_key);

            if ($cryptor->decrypt($this->user->pswd) === $pswd) {

                return true ;
            }
        }

        unset($this->user);
        return false;

    }
}