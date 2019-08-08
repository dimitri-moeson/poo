<?php


namespace App\Controller;

use App\Model\Table\UserTable;
use Core\Auth\CryptAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Render\Render;
use Exception;

/**
 * Class AccountController
 * @package App\Controller
 */
class AccountController extends AppController
{
    /**
     * AccountController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->ctrLog())
        {
            $this->loadModel("User");

            $this->loadService("User");

            if($this->User instanceof UserTable)
            {
                $this->player = $this->User->find( $this->auth->getUser('id')  );
            }

            $this->cryptor = CryptAuth::getInstance($this->auth->getEncryptionKey());
        }
    }

    /**
     *
     */
    public function show(){

    }

    /**
     *
     */
    public function edit(){

        if(Post::getInstance()->submit())
        {
            if($this->User instanceof UserTable)
            {
                $this->User->update( $this->auth->getUser('id'), Post::getInstance()->content() );
            }

        }
        $this->form = new Form($this->player);

    }

    /**
     *
     */
    public function pswd(){

        if(Post::getInstance()->submit())
        {
            $this->UserService->pswd(

                Post::getInstance()->val("old_pswd") ,
                Post::getInstance()->val("new_pswd") ,
                Post::getInstance()->val("rep_pswd")

            );
        }

        $this->form = new Form($this->player);

        $this->form//->init()
            ->input("old_pswd",array("type" => "password","label" => "Ancien Mot de passe"))
            ->input("new_pswd",array("type" => "password","label" => "Nouveau Mot de passe"))
            ->input("rep_pswd",array("type" => "password","label" => "Confirmer Mot de passe"))
        ;

        Render::getInstance()->setView("Account/form");
    }

    /**
     *
     */
    public function mail()
    {
        if(Post::getInstance()->submit())
        {
            $this->UserService->email(

                Post::getInstance()->val("pswd") ,
                Post::getInstance()->val("new_mail") ,
                Post::getInstance()->val("rep_mail")

            );
        }

        $this->form = new Form($this->player);

        $this->form//->init()
            ->input("new_mail",array("type" => "password","label" => "Nouveau Email"))
            ->input("rep_mail",array("type" => "password","label" => "Confirmer Email "))
            ->input("pswd",array("type" => "password","label" => "mot de passe"))
        ;

        Render::getInstance()->setView("Account/form");

    }
}