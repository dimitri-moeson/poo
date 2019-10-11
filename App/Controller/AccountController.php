<?php


namespace App\Controller;

use App\Model\Table\UserTable;
use Core\Auth\CryptAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Render\Render;
use Core\Session\FlashBuilder;
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
            Render::getInstance()->setView("Account/show");

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
                if ($this->User->exists_login(Post::getInstance()->val("login"),$this->player) == false)
                {
                    $this->User->update( $this->auth->getUser('id'), Post::getInstance()->content() );
                }
                else {
                    FlashBuilder::create("already login...","alert");
                }
            }

        }
        $this->form = new Form($this->player);

        $this->form//->init()
            ->input("login", array("type" => "text","label" => "login"))
            ->submit("suivant");
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
                Post::getInstance()->val("new_pswd_conf")

            );
        }
        $pswd =  isset($this->player) ? CryptAuth::getInstance($this->auth->getEncryptionKey())->decrypt($this->player->pswd) : Post::getInstance()->val('pswd');

        $this->form = new Form($this->player);

        $this->form//->init()
            ->pswd("new_pswd",array("conf" => true,"label" => "Nouveau Mot de passe"))
            ->pswd("old_pswd",array("conf" => false ,"label" => "Ancien Mot de passe", "value" => $pswd))
            ->submit("suivant")
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
            if ($this->User->exists_mail(Post::getInstance()->val("new_mail"),$this->player) == false) {
                $this->UserService->email(

                    Post::getInstance()->val("pswd"),
                    Post::getInstance()->val("new_mail"),
                    Post::getInstance()->val("new_mail_conf")

                );
            }else {
                FlashBuilder::create("already mail...","alert");
            }
        }
        $pswd =  isset($this->player) ? CryptAuth::getInstance($this->auth->getEncryptionKey())->decrypt($this->player->pswd) : Post::getInstance()->val('pswd');

        $this->form = new Form($this->player);

        $this->form//->init()
            ->input("new_mail",array("conf" => true ,"type" => "email","label" => "Nouvel Email"))
            ->input("pswd",array("conf" => false ,"type" => "password","label" => "mot de passe" , "value" => $pswd ))
            ->submit("suivant")
        ;

        Render::getInstance()->setView("Account/form");

    }
}