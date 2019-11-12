<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class SqlForm
{
    /**
     * @param $post
     * @return Form
     */
    static function _sql($post)
    {
        $form = new Form($post);

        $form->textarea("request", array(

            'name' => "request" ,
            "label" => "Requête" ,
            "data-show-icon" => 'true',
            "data-content" => '<i class="glyphicon glyphicon-console"></i> Requête',

        ))
            ->submit("Executer");

        return $form ;
    }
}