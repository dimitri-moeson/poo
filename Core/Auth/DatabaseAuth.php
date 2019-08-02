<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 23:08
 */
namespace Core\Auth;

use Core\Database\Database;
use Core\Database\MysqlDatabase;
use Core\Database\QueryBuilder;

class DatabaseAuth
{
    private $db ;

    private  $encryption_key = 'CKXH2U9RPY3EFD70TLS1ZG4N8WQBOVI6AMJ5';

    public function __construct(MysqlDatabase $db)
    {
        $this->db = $db ;
    }

    /**
     * @return
     */
    public function getUserId()
    {
        if($this->logged())
            return $_SESSION['auth'];
    }

    /**
     * @param $var
     * @return mixed
     */
    public function getUser($var)
    {
        if($this->logged()) {
            return $_SESSION['auth'][$var];
        }
    }

    /**
     * @param $login
     * @param $pswd
     * @return bool
     */
    public function login( UserAuth $user = null )
    {
       if($user){

               $_SESSION['auth']['id'] = $user->getId();
               $_SESSION['auth']['roles'] = explode(",", $user->getRoles());
               $_SESSION['auth']['alloweds'] = explode(",", $user->getAlloweds());
               $_SESSION['auth']['forbiddens'] = explode(",", $user->getForbiddens());

               return true ;
       }

       return false ;
    }

    public function logout()
    {
        unset($_SESSION['auth']);
    }

    /**
     * @param $page
     * @return bool
     */
    public function allowed($page){

        if($this->logged()) {

            if ($this->hasRole("admin")) return true;

            if (in_array($page, $_SESSION['auth']['forbiddens'])) return false;

            if (in_array($page, $_SESSION['auth']['alloweds'])) return true;

            //return true ;
        }

        return false ;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        if($this->logged())
            return in_array($role,$_SESSION['auth']['roles']);
    }

    /**
     * @return bool
     */
    public function logged()
    {
        return isset($_SESSION['auth']) ;
    }

    /**
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->encryption_key;
    }
}