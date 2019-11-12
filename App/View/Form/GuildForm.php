<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class GuildForm
{
    /**
     * @param $post
     * @return Form
     */
    static function _Guild($post)
    {
        $form = new Form($post);

        $form
            ->input("name")

            ->textarea("presente",array('type' => 'textarea', 'label' => "Presentation", "class" => "editor"))

            ->submit("Enregistrer");

        return $form ;
    }
}