<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class ArticleForm
{

    /**
     * @param $post
     * @return Form
     */
    static function _article($post,$keywords = [], $categories = [])
    {
        $form = new Form($post);

        $form->input("titre", array('label' => "titre article"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description/Extrait" ))
            ->select("parent_id", array('options' => $categories, 'label' => "Categorie"),$categories)
            ->input("date", array('type' => 'date', 'label' => "ajouté"))
            ->input("type",array("type"=>"hidden", "value"=>"article"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    static function _content($post)
    {
        $form = new Form($post);

        $form
            ->input("contenu", array('type' => 'textarea', 'label' => "contenu", "class" => "editor"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    static function _categorie($post,$keywords = array())
    {
        $form = new Form($post);

        $form->input("titre", array('label' => "Titre Cat"))
            ->input("menu", array('label' => "menu article"))
            ->input("keyword", array('type' => 'textarea', 'label' => "keyword (séparés par des virgules)", "value" => implode(",",$keywords) ))
            ->input("description", array('type' => 'textarea', 'label' => "Description" ))
            //->input("type",array("type"=>"hidden" ,"value" =>"categorie"))
            ->submit("Enregistrer");

        return $form ;
    }
}