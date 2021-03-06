<?php

use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Service\ItemService;
use App\Model\Table\Game\Item\ItemTable;
use Core\Render\Url;

if ($equip instanceof ItemEntity) {

        $fichi = "";

        if (!empty($attributes))
        {
            foreach ($attributes as $stat)
            {
                if ($stat instanceof ItemEntity)
                {
                    $fichi .= " <i class='" . $stat->getImg() . "'></i> : " . $stat->getVal() . "<br/>";
                }
            }
        }



        $id_name = "item";
        $action_name = "inspect";

        $submit = true;

        if ($block == "arene")
        {
            $id_name = "challenger";
            $action_name = "defi";
            $ctrl_name = "arena";
        }
        elseif ($block == "apprentissage")
        {
            $id_name = "craft";
            $action_name = "apprentissage";
            $ctrl_name = "skill";
        }
        elseif ($equip->getType() == "consommable")
        {
            if ($block == "recette") {

                $id_name = "recette";
                $action_name = "craft";
                $ctrl_name = "skill";

            } elseif ($block == "inventaire") {
                $id_name = "item";
                $action_name = "conso";
                $ctrl_name = "item";

            } elseif ($block == "fabrique") {
                $id_name = "item";
                $action_name = "craft";
                $ctrl_name = "skill";
            }

            $attributes = array();
        }
        elseif ($equip->isEquipable()) {
            if ($block == "recette") {
                $id_name = "recette";
                $action_name = "craft";
                $ctrl_name = "skill";

            } elseif ($block == "equipement") {
                $id_name = "retire";
                $action_name = "change";
                $ctrl_name = "default";

            } elseif ($block == "inventaire") {
                $id_name = "equip";
                $action_name = "change";
                $ctrl_name = "default";
            }
            elseif ($block == "fabrique") {
                $id_name = "item";
                $action_name = "fabrique";
                $ctrl_name = "skill";
            }

            if ($block !== "fabrique" && $block !== "recette" && $block !== "apprentissage") {
                $attributes = ItemService::getInstance()->getAttrib($equip);
            }

        }
        else {
            $id_name = "composant";
            $action_name = "inspect";
            $ctrl_name = "item";
            $attributes = array();
        }

        if ($block == "apprentissage" && $known) {
            $submit = false;
        }
    ?>
    <form method="post" class="item" action="<?php echo Url::generate("".$action_name,"".$ctrl_name,"game" , "".$equip->id ) ?>" >
        <input type="hidden" name="place" value="<?php echo $equip->inventaire_id ?>"/>
        <input type="hidden" name="repo" value="<?php echo ItemService::getInstance()->getRepo($equip) ?>"/>

        <input type="hidden" name="<?php echo $action_name ?>" value="<?php echo $equip->type ?>"/>
        <input type="hidden" name="<?php echo $id_name ?>" value="<?php echo $equip->id ?>"/>

        <button class="btn btn-default <?php echo $submit ? 'active' : 'disabled' ?>"
            type="submit" <?php echo $submit ? '' : 'disabled="disabled"' ?>
            id="<?php echo $id_name ?>-<?php echo $equip->id ?>"
            name="<?php echo $id_name ?>-btn"
            value="<?php echo $id_name ?>"
            data-id="<?php echo $equip->inventaire_id ?>"
            data-toggle="tooltip"
            data-html="true"
            title="<?php echo $equip->getName() ?><br/><?php echo $equip->getDescription() ?><br/><?php echo $fichi ?>"

        ><i <?php echo $submit ? '' : 'style="color:lightgrey"' ?> class="<?php echo $equip->img ?>"></i></button>
    </form>

    <ul class='right-click-menu'>
        <li><u><?php echo $equip->inventaire_id ?></u></li>
        <li data-cible="<?php echo $equip->inventaire_id ?>" data-action="jeter" >Jeter</li>
        <!-- a href="?p=test.suppr&id=<?php //echo $equip->inventaire_id ?>" -->
    </ul>

<?php } ?>
