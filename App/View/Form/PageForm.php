<?php


namespace App\View\Form;

use Core\HTML\Form\Form;

class PageForm
{
    /**
     * @param $post
     * @return Form
     */
    static function _article($post,$keywords = [])
    {
        $form = new Form($post);

        $form->input("titre", array('label' => "titre article"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description/Extrait" ))
            ->choice("default",array( "type" => "radio"),array( 1 => "Oui", 0 => "Non"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->submit("Enregistrer");

        return $form ;
    }

    static function _content($post)
    {
        $form = new Form($post);

        $form
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            ->submit("Enregistrer");

        return $form ;
    }

}