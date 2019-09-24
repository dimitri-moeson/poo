<?php
namespace App\Model\Table ;


use App\Model\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

class UserTable extends Table
{
    function exists_login($login){

        $statement = QueryBuilder::init()->select('*')
            ->from('user')
            ->where('login = :id')
        ;

        $mot = $this->request( $statement , array("id" => $login), false , UserEntity::class);

        if($mot) return true ;

        return false ;

    }

    function exists_mail($login){

        $statement = QueryBuilder::init()->select('*')
            ->from('user')
            ->where('mail = :id')
        ;

        $mot = $this->request( $statement , array("id" => $login), false , UserEntity::class);

        if($mot) return true ;

        return false ;

    }
}