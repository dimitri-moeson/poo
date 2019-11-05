<?php use App\Model\Entity\Game\Item\ItemEntity;
use Core\Render\Url;

if(isset($post)){ ?>
    <ul class='nav nav-tabs nav-justified'>
        <li><a href=#' ><?php echo $post->type ?></a></li>
        <li class='<?php echo $p =="single" ? 'active' :'' ?>'><a href='<?php echo Url::generate("single","Item","admin",$post->id);?>' >Params</a></li>
        <li class='<?php echo $p =="descript" ? 'active' :'' ?>'><a href='<?php echo Url::generate("descript","Item","admin",$post->id);?>' >Descript</a></li>
        <li class='<?php echo $p =="icone" ? 'active' :'' ?>'><a href='<?php echo Url::generate("icone","Item","admin",$post->id);?>' >Icone</a></li>

    <?php if( in_array($post->type , ItemEntity::type_arr["aventure"]) ) { ?>

        <li class='<?php echo $p =="mission" ? 'active' :'' ?>'><a href='<?php echo Url::generate("mission","Item","admin",$post->id);?>' >mission</a></li>


        <?php } ?>

    </ul>
<?php } ?>