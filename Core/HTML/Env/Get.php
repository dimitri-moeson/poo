<?php


namespace Core\HTML\Env;


class Get extends GlobalRequest
{
    /**
     * confirme l'envoie du formulaire
     * @return bool
     */
    public function submit() {
        if(strtoupper($_SERVER['REQUEST_METHOD']) === 'GET'){
            return isset($_GET) && !empty($_GET);
        }
    }
}