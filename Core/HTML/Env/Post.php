<?php
namespace Core\HTML\Env ;

class Post extends GlobalRequest
{
    /**
     * confirme l'envoie du formulaire
     * @return bool
     */
    public function submited(){

        return !empty($_POST);
    }
}