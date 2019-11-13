<?php


namespace App\View\Form;


use App;
use Core\HTML\Env\File;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class FileForm
{
    /**
     * @param $post
     * @return Form
     */
    static function form_file($type = "picture")
    {
        $form = new Form(array( 'type' => $type ));

        $form->input("type", array('type' => "hidden"))
            ->input("nom", array('label' => "Nom"))
            ->input("src", array('type' => "file"))
            ->submit("Enregistrer");

        return $form ;
    }


}