<?php
namespace App\View\Form;


use App\Model\Entity\Game\Item\ItemEntity;
use Core\HTML\Form\Form;
use HTML\Icon\Icon;

class ItemForm
{
    /**
     * ItemForm constructor.
     * @param $post
     */
    function __construct($post)
    {
        $form = new Form($post);

        $form->input("name", array('label' => "Nom"));
        $form->addInput("img", self::select_img($post->img) ) ;
        $form->addInput("type", self::select_typ($post->type) ) ;
        $form->addInput("objet", self::select_obj($post->objet) ) ;


            //$form->select("type", array('options' => ItemEntity::type_arr , 'label' => "type"), ItemEntity::type_arr );
            //$form->select("objet", array('options' => ItemEntity::categorie_arr , 'label' => "objet"), ItemEntity::categorie_arr );
         /*   if(is_array($post) || !isset($post->id))
            {
            }
*/
        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif"))
            ->input("vie", array('label' => "Moyenne"));

       /* if(is_object($post) && isset($post->id)){

        }*/

        $form->submit("Enregistrer");

    }

    /**
     * @param $selected
     * @return string
     */
    private static function select_obj($name="objet",$selected, $grp = null ){

        $cnt = "<label>Objet</label><br/><select class='show-tick' name='$name' data-live-search='true' >";
        $cnt .= "<option>...</option>";

        if(is_null($grp)) {
            foreach (ItemEntity::categorie_arr as $group => $icons) {

                $cnt .= "<optgroup label='$group'>";

                foreach ($icons as $class) {

                    $cnt .= "<option " . ($class === $selected ? "selected" : "") . "  value='$class' >$class</option>";
                }

                $cnt .= "</optgroup>";
            }
        }
        else {

            foreach (ItemEntity::categorie_arr[$grp] as $class) {

                    $cnt .= "<option " . ($class === $selected ? "selected" : "") . "  value='$class' >$class</option>";

            }

        }

        $cnt .= "</select>";

        return $cnt ;

    }

    /**
     * @param $selected
     * @return string
     */
    private static function select_typ($selected, $type = null ){

        $cnt = "<label>Type</label><br/><select class='show-tick' name='type' data-live-search='true' >";
        $cnt .= "<option>...</option>";

        if(is_null($type)) {
            foreach (ItemEntity::type_arr as $group => $icons ){

                $cnt .= "<optgroup label='$group'>";

                foreach ( $icons as $class ) {

                    $cnt .= "<option ".($class===$selected ? "selected" : "" )."  value='$class' >$class</option>";
                }

                $cnt .= "</optgroup>";
            }
        }
        else {

            foreach (ItemEntity::type_arr[$type] as $class) {

                $cnt .= "<option " . ($class === $selected ? "selected" : "") . "  value='$class' >$class</option>";

            }
        }

        $cnt .= "</select>";

        return $cnt ;

    }

    /**
     * @param $selected
     * @return string
     */
    private static function select_img($selected , $type = null ){

        $cnt = "<label>Icone</label><br/><select size='5' class='custom-select show-tick' name='img' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>...</option>";

        foreach (Icon::icon_list as $group => $icons ){

            $cnt .= "<optgroup label='$group'>";

            foreach ( $icons as $class => $icon) {

                $cnt .= "<option ".($class===$selected ? "selected" : "" )."  value='$class' data-content='<i class=\"$class\"></i> $icon'>$icon</option>";
            }

            $cnt .= "</optgroup>";
        }

        $cnt .= "</select>";

        return $cnt ;
    }

    private static function checkbox_img($selected , $type = null ){

        $cnt = "<label>Icone</label><br/>";

        foreach (Icon::icon_list as $group => $icons )
        {
            $cnt .= "<br/>";
            $cnt .= "<fieldset>";
            $cnt .= "<legend>$group</legend>";

            foreach ( $icons as $class => $icon)
            {
                $cnt .= "<div class='col-sm-3'>";
                $cnt .= "<input  ".($class===$selected ? "checked" : "" )." type='radio' name='img' value='$class' />&nbsp;<i class='$class'></i>&nbsp;".$icon;
                $cnt .= "</div>";
            }

            $cnt .= "</fieldset>";
        }

        return $cnt ;
    }

    /**
     * @param $post
     * @return Form
     */
    static function _article($post)
    {
        $form = new Form($post);

        $form->addInput("type", self::select_typ(@$post->type ?? $post["type"]) ) ;
        $form->addInput("objet", self::select_obj("objet",@$post->objet) ) ;

        $form->input("name", array('label' => "Nom"));
        $form->input("vie", array('label' => "Moyenne"));

        $form->submit("Enregistrer");


        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    static function _descript($post)
    {
        $form = new Form($post);
        $form->input("description", array('type' => 'textarea', 'label' => "Descriptif", "class" => "editor"));

        $form->submit("Enregistrer");


        return $form ;
    }

    /**
     * @param $post
     * @return Form
     */
    static function _icon($post){

        /** @var Form $form */
        $form = new Form($post);
        $form->addInput("img", self::checkbox_img(@$post->img) ) ;

        $form->submit("Enregistrer");

        return $form ;

    }

    /**
     * @param $post
     * @param null $link
     * @return Form
     */
    private static function _inventaire($post, $link = null){

        /** @var Form $form */
        $form =new Form($link);

        if (!empty($link)) {
            if(isset($link->id))
                $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"edition"))
                    ->input("id", array("type"=>"hidden","name"=>"id","value"=>@$link->id));
            else
                $form->input("action", array("type"=>"hidden","name"=>"action","value"=>"ajout"));
        }

        $form->input("parent_id", array("type"=>"hidden","name"=>"parent_id","value"=>@$link->parent_id))
            ->input("rubrique", array("type"=>"hidden","name"=>"rubrique","value"=>@$post->type));

        return $form ;
    }

    /**
     * @param $post
     * @param null $link
     * @return Form
     */
    static function _mission($post, $link = null, $items){

        $form = self::_inventaire($post, $link);

        $form->input("val", array("name"=>"val","label"=>"quantité"));

        $form->select("child_id",array("name"=>"child_id","value"=>@$link->child_id,"label"=>"cible"),$items);

        $form->addInput("type", self::select_obj("type",@$link->type,"mission") );

        /** elseif( in_array($post->type , ItemEntity::type_arr["classe"]) ) { }
        elseif( in_array($post->type , ItemEntity::type_arr["faune"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["arme_1_main"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["equipement"]) ) { }
        elseif( in_array($post->type , ItemEntity::type_arr["arme_2_main"]) ) {  }
        elseif( in_array($post->type , ItemEntity::type_arr["batiment"]) ) { }**/
        $form->submit("reg");

        return $form ;
    }

    static function _attribut($post, $link = null , $items )
    {
        $form = self::_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"statistique"));
        $form->input("val", array("name"=>"val","label"=>"score"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "caractéristique"), $items);

        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg");

        return $form;
    }

    static function _ressource($post, $link = null , $items )
    {
        $form = self::_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"ressource"));
        $form->input("val", array("name"=>"val","label"=>"score"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "ressource"), $items);

        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg");

        return $form;
    }

    /**
     * @param $post
     * @param null $link
     * @param $items
     * @return Form
     */
    static function _craft($post, $link = null,$items )
    {

        $form = self::_inventaire($post, $link);

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"composant"));
        $form->input("val", array("name"=>"val","label"=>"quantité"));

        $form->select("child_id", array("name" => "child_id", "value" => @$link->child_id,"label" => "composant"), $items);

        $form->submit("reg");

        return $form;
    }
}