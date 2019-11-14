<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class PersonnageForm
{
    static function admin_perso($post)
    {
        $form = new Form($post);

        $form->input("name", array('name' => "Name","label"=>"Nom"))
            ->choice("sexe", array('name' => "sexe","label"=>"Genre"),array( 1 => "homme" , 2 => "femme"))
            ->submit("Enregistrer");

        return $form ;
    }
}