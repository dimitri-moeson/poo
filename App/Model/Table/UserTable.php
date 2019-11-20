<?php
namespace App\Model\Table ;

use App\Model\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

class UserTable extends Table
{
    function exists_login($login, UserEntity $user = null ){

        $statement = QueryBuilder::init()->select('*')
            ->from('user')
            ->where('login = :login')
        ;

        $attr = array("login" => $login);

        if(!is_null($user)){

            $statement->where(' id != :id ');
            $attr["id"] = $user->id;
        }

        $mot = $this->request( $statement ,$attr , false , UserEntity::class);

        if($mot) return true ;

        return false ;

    }

    function exists_mail($mail, UserEntity $user = null){

        $statement = QueryBuilder::init()->select('*')
            ->from('user')
            ->where('mail = :mail')
        ;

        $attr = array("mail" => $mail);

        if(!is_null($user)){

            $statement->where(' id != :id ');
            $attr["id"] = $user->id;
        }

        $mot = $this->request( $statement ,$attr , false , UserEntity::class);

        if($mot) return true ;

        return false ;

    }
}