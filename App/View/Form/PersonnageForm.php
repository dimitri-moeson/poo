<?php


namespace App\View\Form;


use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service\PersonnageService;
use Core\HTML\Form\Form;

class PersonnageForm
{
    /**
     * @param PersonnageEntity $post
     * @param $classes
     * @param $factions
     * @param $races
     * @return Form
     */
    static function admin_perso(PersonnageEntity $post,$classes,$factions,$races)
    {
        $form = new Form($post);

        $form->addInput("titre1", "<fieldset><legend>Personnage</legend>")

            ->textarea("description", array(

                "surround" => array("class" =>  "pull-right col-sm-6"),
                "option" => array("style" => "height:200px")
            ))
            ->input("name", array(

                "surround" => array("class" =>  "col-sm-2"),
                'name' => "name",
                "label"=>"Nom"
            ))
            ->input("vie", array(

                "surround" => array("class" =>  "col-sm-2"),
                'name' => "vie",
                "label"=>"vie",
                "value" => $post->vie
            ))
            ->select("status", array(

                "surround" => array("class" =>  "col-sm-2")

            ),ItemEntity::$categorie_arr["status"])


            ->choice("sexe", array(

                "surround" => array("class" =>  "col-sm-6"),
                'name' => "sexe",
                "label"=>"Genre" ,
                "value" => $post->sexe

            ),array( 1 => "homme" , 2 => "femme"))

            ->addInput("titre1_close", "</fieldset>")
            ->addInput("titre2", "<fieldset><legend>Select</legend>")

            ->addInput("type", self::selectItm($post->type,$classes,"classe","type"))
            ->addInput("faction_id", self::selectItm($post->faction_id,$factions,"faction","faction_id"))
            ->addInput("race_id", self::selectItm($post->race_id,$races,"race","race_id"))

            ->addInput("titre2_close", "</fieldset>")
            ->addInput("titre3", "<fieldset><legend>Stats</legend>")
        ;

        foreach( $post->getStats()->getContainer() as $x => $stat )
        {
            if($stat instanceof ItemEntity )
            {
                $form->number( $stat->id , array(

                    "surround" => array("class" =>  "col-sm-3"),
                    'name' => "stats[".$stat->id."]" ,
                    "label"=>$stat->getName(),
                    "value" => $stat->getVal()
                ));
            }
        }
        $form->addInput("titre3_close", "</fieldset>");

        $form->submit("Enregistrer");

        return $form ;
    }

    /**
     * @param $selected
     * @param null $icons
     * @param $label
     * @param $name
     * @return string
     */
    private static function selectItm($selected , $icons = null, $label , $name )
    {
        $cnt = "<div class='col-sm-4'>
                    <label>$label</label><br/>
                    <select id='$name' name='$name' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>---</option>";

        foreach ( $icons as  $icon)
        {
            $nme = str_replace("'","-", $icon->getName());
            $cnt .= "<option ".($icon->id===$selected ? "selected" : "" )."  
            value='".$icon->id."' 
            data-content='<i class=\"".$icon->getImg()."\"></i> ".$nme."'/>";
            //    $icon->getName()."</option>";
        }

        $cnt .= "</select></div>";

        return $cnt ;
    }
}