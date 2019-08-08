<?php


namespace Core\HTML\Env;


class Ajax extends GlobalRequest
{
    /**
     * @fn          isAjax
     * @brief       Retourne true si c'est une requete ajax, false sinon
     * @public
     *
     * @return      bool
     *
     */
    public function submit()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}