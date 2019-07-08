<?php use \Core\Render\Render ; ?>

<table id='equipement' class='<?php echo  $sexe ?> table table-borderless'>
    <tr>
        <td></td>
        <td id='casque'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["casque"],
                "block" => "equipement"

            )) ; ?></td>
        <td id='amulette'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["amulette"],
                "block" => "equipement"

            )); ?></td>
    </tr>
    <tr>
        <td id='epaule'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["epaule"],
                "block" => "equipement"

            )) ?></td>
        <td id='torse'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["torse"],
                "block" => "equipement"

            )) ?></td>
        <td id='brassard'><?php echo Render::getInstance()->block("item.btn",array(

            "equip" => $equipements["brassard"],
            "block" => "equipement"

        )) ?></td>
    </tr>
    <tr>
        <td id='gant'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["gant"],
                "block" => "equipement"

            )) ?></td>
        <td id='ceinture'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["ceinture"],
                "block" => "equipement"

            ))  ?></td>
        <td id='bague'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["bague"],
                "block" => "equipement"

            )) ?></td>
    </tr>
    <tr>
        <td rowspan="2" id='arme'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["arme"],
                "block" => "equipement"

            ))  ?></td>
        <td id='jambe'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["jambe"],
                "block" => "equipement"

            )) ?></td>
        <td rowspan="2" id='bouclier'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["bouclier"],
                "block" => "equipement"

            ))  ?></td>
    </tr>
    <tr>
        <td id='botte'><?php echo Render::getInstance()->block("item.btn",array(

                "equip" => $equipements["botte"],
                "block" => "equipement"

            ))  ?></td>
    </tr>
</table>