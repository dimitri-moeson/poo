<?php
namespace App\View\Form;


use App\Model\Entity\Game\Item\ItemEntity;
use Core\HTML\Form\Form;
use Core\HTML\Icon\Icon;

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
    private static function select_obj($name="objet",$selected, $grp = null, $class = "row col-sm-12" ){
        //class='show-tick'
        $cnt = "<div class='$class'><label>Objet</label><br/><select  name='$name' data-live-search='true' >";
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

        $cnt .= "</select></div>";

        return $cnt ;

    }

    /**
     * @param $selected
     * @return string
     */
    private static function select_typ($selected, $type = null ){
        //class='show-tick'
        $cnt = "<div class='row col-sm-12'><label>Type</label><br/><select  name='type' data-live-search='true' >";
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

        $cnt .= "</select></div>";

        return $cnt ;

    }

    /**
     * @param $selected
     * @return string
     */
    private static function select_img($selected , $type = null ){
        //class='custom-select show-tick'
        $cnt = "<label>Icone</label><br/><select  name='img' data-show-icon='true' data-live-search='true'>";

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
        $form = new Form($link);

        if (!is_null($link) && !empty($link))
        {
            if(isset($link->id) && !empty($link->id))
            {
                $form->input("action", array("type" => "hidden", "name" => "action", "value" => "edition"))
                    ->input("id", array("type" => "hidden", "name" => "id", "value" => @$link->id));
            }
            else
            {
                $form->input("action", array("type" => "hidden", "name" => "action", "value" => "ajout"));
            }
        }

        $form->input("parent_id", array("type"=>"hidden","name"=>"parent_id","value"=>@$link->parent_id))
            ->input("rubrique", array("type"=>"hidden","name"=>"rubrique","value"=>@$post->type));

        return $form ;
    }

    private static function selectChild($selected , $icons = null, $label, $index ){
        //class='custom-select show-tick'
        $cnt = "<div class='col-sm-4'>
                    <label>$label</label><br/>
                    <select id='child_id_$index' name='child_id' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>---</option>";

        foreach ( $icons as  $icon) {

            $cnt .= "<option ".($icon->id===$selected ? "selected" : "" )."  
            value='".$icon->id."' 
            data-content='<i class=\"".$icon->getImg()."\"></i> ".$icon->getName()."'/>";
            //    $icon->getName()."</option>";
        }

        $cnt .= "</select></div>";

        return $cnt ;
    }


    /**
     * @param $post
     * @param null $link
     * @return Form
     */
    static function _mission($post, $link = null, $items, $index){

        $form = self::_inventaire($post, $link);

        $surround = array("type" => "div", "class" => "col-sm-4");

        $form->addInput("type", self::select_obj("type",@$link->type,"mission","col-sm-4") );
        $form->addInput("child_id", self::selectChild(@$link->child_id,$items,"cible", $index ));
        $form->input("val", array("name"=>"val","label"=>"quantité", "surround" => $surround , "id" => "val_".$index));

        /**
            elseif( in_array($post->type , ItemEntity::type_arr["classe"]) ) { }
            elseif( in_array($post->type , ItemEntity::type_arr["faune"]) ) {  }
            elseif( in_array($post->type , ItemEntity::type_arr["arme_1_main"]) ) {  }
            elseif( in_array($post->type , ItemEntity::type_arr["equipement"]) ) { }
            elseif( in_array($post->type , ItemEntity::type_arr["arme_2_main"]) ) {  }
            elseif( in_array($post->type , ItemEntity::type_arr["batiment"]) ) { }
         **/

        $form->submit("reg",array("surround" => $surround));

        return $form ;
    }

    static function _attribut($post, $link = null , $items,$index )
    {
        $form = self::_inventaire($post, $link);

        $surround = array("type" => "div", "class" => "col-sm-3");

        //$form->addInput("print_r", print_r($link,1));
        $form->input("type", array("type"=>"hidden" ,"name"=>"type","value"=>"statistique"));
        $form->addInput("child_id", self::selectChild(@$link->child_id,$items,"caractéristique",$index));
        $form->input("val", array("name"=>"val", "surround" => $surround ,"label"=>"score", "id" => "val_".$index));
        $form->addInput("caract", self::select_obj("caract",@$link->caract,"action","col-sm-4" ));

        //$form->select("child_id", array("name" => "child_id", "surround" => $surround , "value" => @$link->child_id,"label" => "caractéristique"), $items);
        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg",array("surround" => $surround));

        return $form;
    }

    static function _ressource($post, $link = null , $items,$index )
    {
        $form = self::_inventaire($post, $link);

        $surround = array("type" => "div", "class" => "col-sm-4");

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"ressource"));
        $form->addInput("child_id", self::selectChild(@$link->child_id,$items,"ressource",$index));
        $form->input("val", array("name"=>"val", "surround" => $surround ,"label"=>"score", "id" => "val_".$index));

        //$form->select("child_id", array("name" => "child_id", "surround" => $surround , "value" => @$link->child_id,"label" => "ressource"), $items);
        //$form->addInput("type", ItemForm::select_obj("type", @$link->type, "personnage"));

        $form->submit("reg",array("surround" => $surround));

        return $form;
    }

    /**
 * @param $post
 * @param null $link
 * @param $items
 * @return Form
 */
    static function _craft($post, $link = null,$items,$index )
    {

        $form = self::_inventaire($post, $link);

        $surround = array("type" => "div", "class" => "col-sm-4");

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"composant"));
        $form->input("val", array("name"=>"val", "surround" => $surround ,"label"=>"quantité", "id" => "val_".$index));

        //$form->select("child_id", array("name" => "child_id", "surround" => $surround , "value" => @$link->child_id,"label" => "composant"), $items);
        $form->addInput("child_id", self::selectChild(@$link->child_id,$items,"composant",$index ));

        $form->submit("reg",array("surround" => $surround));

        return $form;
    }

    /**
     * @param $post
     * @param null $link
     * @param $items
     * @return Form
     */
    static function _catalogue($post, $link = null,$items,$index )
    {

        $form = self::_inventaire($post, $link);

        $surround = array("type" => "div", "class" => "col-sm-4");

        $form->input("type", array("type"=>"hidden","name"=>"type","value"=>"composant"));
        $form->input("val", array("type"=>"hidden","name"=>"val","value"=> -1 ));

            //array("name"=>"val", "surround" => $surround ,"label"=>"quantité", "id" => "val_".$index));

        //$form->select("child_id", array("name" => "child_id", "surround" => $surround , "value" => @$link->child_id,"label" => "composant"), $items);
        $form->addInput("child_id", self::selectChild(@$link->child_id,$items,"composant",$index ));

        $form->submit("reg",array("surround" => $surround));

        return $form;
    }
}