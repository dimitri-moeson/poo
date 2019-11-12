<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class PersonnageForm
{
    static function admin_perso($post)
    {
        $form = new Form($post);

        $form->input("titre", array('name' => "Nom"))
            ->choice("sexe", array('name' => "genre"),array( 1 => "homme" , 2 => "femme"))
            ->submit("Enregistrer");

        return $form ;
    }
}