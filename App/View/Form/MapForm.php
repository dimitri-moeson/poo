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

        // size='5' class='custom-select show-tick'
        $cnt = "<label>Terrain</label><br/><select name='terrain' data-show-icon='true' data-live-search='true'>";

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

        // size='5' class='custom-select show-tick'
        $cnt = "<label>Installé</label><br/><select name='install' data-show-icon='true' data-live-search='true'>";

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