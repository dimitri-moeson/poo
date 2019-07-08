<?php


namespace App\View\Form;


use Core\HTML\Form\Form;

class MapForm
{
    function __construct($post)
    {
        $form = new Form($post);
    }

    static function selectTerrain($selected , $icons = null ){

        $cnt = "<label>Terrain</label><br/><select size='5' class='custom-select show-tick' name='terrain' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>---</option>";

        foreach ( $icons as  $icon) {

            $cnt .= "<option ".($icon->id===$selected ? "selected" : "" )."  
            value='".$icon->id."' 
            data-content='<i class=\"".$icon->getImg()."\"></i> ".$icon->getName()."'>".
                $icon->getName()."</option>";
        }

        $cnt .= "</select>";

        return $cnt ;
    }

    static function selectInstall($selected , $icons = null ){

        $cnt = "<label>Installé</label><br/><select size='5' class='custom-select show-tick' name='install' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>---</option>";

        foreach ( $icons as $icon) {

            $cnt .= "<option ".($icon->id===$selected ? "selected" : "" )."  
            value='".$icon->id."' 
            data-content='<i class=\"".$icon->getImg()."\"></i> ".$icon->getName()."'>".
                $icon->getName()."</option>";
        }

        $cnt .= "</select>";

        return $cnt ;
    }
}