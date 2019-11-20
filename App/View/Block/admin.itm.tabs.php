<?php use App\Model\Entity\Game\Item\ItemEntity;
use Core\Render\Url;
?>

<?php if(isset($post)){ ?>

    <ul class='nav nav-tabs nav-justified'>

        <li class='<?php echo $p =="single" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("single","Item","admin",$post->id);?>' >Params</a></li>
        <li class='<?php echo $p =="descript" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("descript","Item","admin",$post->id);?>' >Descript</a></li>
        <li class='<?php echo $p =="icone" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("icone","Item","admin",$post->id);?>' >Icone</a></li>

    <?php if( in_array($post->type , ItemEntity::$type_arr["aventure"]) ) { ?>

        <li class='<?php echo $p =="mission" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("mission","Item","admin",$post->id);?>' >Mission</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["politique"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["classe"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["faune"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["competence"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["arme_1_main"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>
        <li class='<?php echo $p =="craft" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("craft","Item","admin",$post->id);?>' >Craft</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["arme_2_main"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>
        <li class='<?php echo $p =="craft" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("craft","Item","admin",$post->id);?>' >Craft</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["equipement"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>
        <li class='<?php echo $p =="craft" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("craft","Item","admin",$post->id);?>' >Craft</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["item"]) ) { ?>

        <li class='<?php echo $p =="ressource" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("ressource","Item","admin",$post->id);?>' >Ressource</a>
        </li>
        <li class='<?php echo $p =="attribut" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("attribut","Item","admin",$post->id);?>' >Attribut</a>
        </li>
        <li class='<?php echo $p =="craft" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("craft","Item","admin",$post->id);?>' >Craft</a>
        </li>

    <?php } elseif( in_array($post->type , ItemEntity::$type_arr["batiment"]) ) { ?>

        <li class='<?php echo $p =="catalogue" ? 'active' : '' ?>'>
            <a href='<?php echo Url::generate("catalogue","Item","admin",$post->id);?>' >Catalogue</a>
        </li>

    <?php } ?>

    </ul>
    <br/>
<?php } ?>