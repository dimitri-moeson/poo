<?php
namespace Core\HTML\Env ;

class Post extends GlobalRequest
{
    /**
     * confirme l'envoie du formulaire
     * @return bool
     */
    public function submit(){

        return isset($_POST) && !empty($_POST);
    }
}