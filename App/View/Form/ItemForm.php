<?php
namespace App\View\Form;


use App\Model\Entity\Game\Item\ItemEntity;
use Core\HTML\Form\Form;

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
    static function select_obj($selected, $grp = null ){

        $cnt = "<label>Objet</label><br/><select class='show-tick' name='objet' data-live-search='true' >";
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
    static function select_typ($selected){

        $cnt = "<label>Type</label><br/><select class='show-tick' name='type' data-live-search='true' >";
        $cnt .= "<option>...</option>";

        foreach (ItemEntity::type_arr as $group => $icons ){

            $cnt .= "<optgroup label='$group'>";

            foreach ( $icons as $class ) {

                $cnt .= "<option ".($class===$selected ? "selected" : "" )."  value='$class' >$class</option>";
            }

            $cnt .= "</optgroup>";
        }

        $cnt .= "</select>";

        return $cnt ;

    }

    /**
     * @param $selected
     * @return string
     */
    static function select_img($selected , $type = null ){

        $cnt = "<label>Icone</label><br/><select size='5' class='custom-select show-tick' name='img' data-show-icon='true' data-live-search='true'>";

        $cnt .= "<option>...</option>";

        foreach (self::icon_list as $group => $icons ){

            $cnt .= "<optgroup label='$group'>";

            foreach ( $icons as $class => $icon) {

                $cnt .= "<option ".($class===$selected ? "selected" : "" )."  value='$class' data-content='<i class=\"$class\"></i> $icon'>$icon</option>";
            }

            $cnt .= "</optgroup>";
        }

        $cnt .= "</select>";

        return $cnt ;
    }

    static function checkbox_img($selected , $type = null ){

        $cnt = "<label>Icone</label><br/>";

        foreach (self::icon_list as $group => $icons )
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

    const icon_list = array(

        "amulette" => array(

            "ra ra-gem-pendant" => "amulette"  ,
            "fas fa-medal" =>"medaille"  ,
            "fas fa-award" => "cocarde" ,

        ),

        "batteries" => array(

            "ra ra-batteries" => "batteries",
            "ra ra-battery-0" => "battery-0",
            "ra ra-battery-25" => "battery-25",
            "ra ra-battery-50" => "battery-50",
            "ra ra-battery-75" => "battery-75",
            "ra ra-battery-100" => "battery-100",
            "ra ra-battery-black" => "battery-black",
            "ra ra-battery-negative" => "battery-negative",
            "ra ra-battery-positive" => "battery-positive",
            "ra ra-battery-white" => "battery-white",
        ),

        "electronics" => array(

            "ra ra-alien-fire" => "alien-fire",

            "ra ra-clockwork" => "clockwork",
            "ra ra-cog" => "cog",
            "ra ra-cog-wheel" => "cog-wheel",
            "ra ra-defibrillate" => "defibrillate",
            "ra ra-energise" => "energise",
            "ra ra-fast-ship" => "fast-ship",
            "ra ra-gamepad-cross" => "gamepad-cross",
            "ra ra-gear-heart" => "gear-heart",
            "ra ra-gears" => "gears",
            "ra ra-laser-site" => "laser-site",
            "ra ra-lever" => "lever",
            "ra ra-light-bulb" => "light-bulb",
            "ra ra-lighthouse" => "lighthouse",
            "ra ra-load" => "load",
            "ra ra-magnet" => "magnet",
            "ra ra-microphone" => "microphone",
            "ra ra-nuclear" => "nuclear",
            "ra ra-radar-dish" => "radar-dish",
            "ra ra-radioactive" => "radioactive",
            "ra ra-reactor" => "reactor",
            "ra ra-recycle" => "recycle",
            "ra ra-regeneration" => "regeneration",
            "ra ra-repair" => "repair",
            "ra ra-robot-arm" => "robot-arm",
            "ra ra-rss" => "rss",
            "ra ra-satellite" => "satellite",
            "ra ra-save" => "save",
            "ra ra-speech-bubble" => "speech-bubble",
            "ra ra-speech-bubbles" => "speech-bubbles",
            "ra ra-surveillance-camera" => "surveillance-camera",
            "ra ra-telescope" => "telescope",
            "ra ra-tesla" => "tesla",
            "ra ra-unplugged" => "unplugged",
            "ra ra-wifi" => "wifi",
            "ra ra-wireless-signal" => "wireless-signal",

        ),

        "anneau" => array(

             "fas fa-ring" => "bague 1",
             "ra ra-fire-ring" => "bague de feu"

        ),

        "batiment" => array(

            "fa fa-institution" => "institut",
            "fa fa-bank" => "bank",
            "ra ra-capitol" => "capitol",
            "fa fa-building" => "building",
            // "fa fa-building-o"   =>"building 2" ,
            "fa fa-home"  =>  "maison",
            "ra ra-tower"  =>"tour" ,
            "fa fa-industry" => "industry",
            "ra ra-guarded-tower" => "guarded-tower",
            "ra ra-heart-tower" => "heart-tower",

        ),

        "competence" => array(

            "ra ra-spinning-sword" => "spinning-sword",
            "ra ra-bolt-shield" => "bolt-shield"

        ),

        "consommable" => array(

            "ra ra-bottle-vapors" => "bottle-vapors",
            "ra ra-bottled-bolt" => "bottled-bolt",
            "ra ra-broken-bottle" => "broken-bottle",
            "ra ra-bubbling-potion" => "bubbling-potion",
            "ra ra-heart-bottle" => "heart-bottle",
            "ra ra-corked-tube" => "corked-tube",
            "ra ra-fizzing-flask" => "fizzing-flask",
            "ra ra-flask" => "flask",
            "ra ra-round-bottom-flask ra-hover" => "round-bottom-flask",
            "ra ra-vial" => "vial",
            "ra ra-vase" => "vase",

        ),

        "forge" => array(

            "ra ra-forging"  => "forging",
            "ra ra-anvil" => "anvil"

        ),

        "pnj" => array(

            "fa fa-female"    =>"femme",
            "fa fa-male"    => "homme",
            "fa fa-child"  => "enfant",

        ),

        "mains" => array(

            "fa fa-sign-language" => "language",
            "fas fa-mitten" => "mitten",

            "glyphicon glyphicon-thumbs-up" => "thumbs-up",
            "glyphicon glyphicon-thumbs-down" => "thumbs-down",
            "glyphicon glyphicon-hand-right" => "hand-right",
            "glyphicon glyphicon-hand-left" => "hand-left",
            "glyphicon glyphicon-hand-up" => "hand-up",
            "glyphicon glyphicon-hand-down" => "hand-down",

            "ra ra-hand" => "hand",
        ),

        "metaux" => array(

            "ra ra-gold-bar"  => "lingot"
        ),

        "pieds" => array(

            "ra ra-boot-stomp" => "boot-stomp",
            "ra ra-shoe-prints" =>  "semelle",
            "ra ra-footprint" => "nus" ,
        ),

        "plantes" => array(

            "ra ra-clover" => "clover",
            "ra ra-daisy" => "daisy",
            "ra ra-dead-tree" => "dead-tree",
            "ra ra-flower" => "flower",
            "ra ra-flowers" => "flowers",
            "ra ra-grass" => "grass",
            "ra ra-grass-patch" => "grass-patch",
            "ra ra-leaf" => "leaf",
            "ra ra-palm-tree" => "palm-tree",
            "ra ra-pine-tree" => "pine-tree",
            "ra ra-sprout" => "sprout",
            "ra ra-super-mushroom" => "super-mushroom",
            "ra ra-trefoil-lily" => "trefoil-lily",
            "ra ra-zigzag-leaf" => "zigzag-leaf",

            "glyphicon glyphicon-leaf" => "leaf",
            "glyphicon glyphicon-tree-conifer" => "tree-conifer",
            "glyphicon glyphicon-tree-deciduous" => "tree-deciduous",

        ),

        "statut" => array(


            "ra ra-double-team" => "double-team",
            "ra ra-falling" => "falling",
            "ra ra-muscle-fat" => "muscle-fat",
            "ra ra-muscle-up" => "muscle-up",
            "ra ra-player" => "player",
            "ra ra-player-despair" => "despair",
            "ra ra-player-dodge" => "dodge",
            "ra ra-player-king" => "king",
            "ra ra-player-lift" => "lift",
            "ra ra-player-pain" => "pain",
            "ra ra-player-pyromaniac" => "pyromaniac",
            "ra ra-player-shot" => "shot",
            "ra ra-player-teleport" => "teleport",
            "ra ra-player-thunder-struck" => "thunder-struck",
        ),

        "tete" => array(

            "ra ra-knight-helmet" => "knight-helmet",
            "ra ra-helmet" => "helmet",
            "ra ra-horns" => "horns",
            "ra ra-crown" => "crown",
            "ra ra-crown-of-thorns" => "crown-of-thorns",
            "ra ra-queen-crown" => "queen-crown",
            "ra ra-cracked-helm" => "cracked-helm",
        ),

        "torse" => array(

            "ra ra-vest" => "veste"
        ),

        "Animaux" => array(

            "ra ra-beetle" => "beetle",
            "ra ra-bird-claw" => "bird-claw",
            "ra ra-butterfly" => "butterfly",
            "ra ra-cat" => "cat",
            "ra ra-dinosaur" => "dinosaur",
            "ra ra-dragon" => "dragon",
            "ra ra-dragonfly" => "dragonfly",
            "ra ra-eye-monster" => "eye-monster",
            "ra ra-fairy" => "fairy",
            "ra ra-fish" => "fish",
            "ra ra-fox" => "fox",
            "ra ra-gecko" => "gecko",
            "ra ra-hydra" => "hydra",
            "ra ra-insect-jaws" => "insect-jaws",
            "ra ra-lion" => "lion",
            "ra ra-love-howl" => "love-howl",
            "ra ra-maggot" => "maggot",
            "ra ra-octopus" => "octopus",
            "ra ra-rabbit" => "rabbit",
            "ra ra-raven" => "raven",
            "ra ra-sea-serpent" => "sea-serpent",
            "ra ra-seagull" => "seagull",
            "ra ra-shark" => "shark",
            "ra ra-sheep" => "sheep",
            "ra ra-snail" => "snail",
            "ra ra-snake" => "snake",
            "ra ra-spider-face" => "spider-face",
            "ra ra-spiral-shell" => "spiral-shell",
            "ra ra-two-dragons" => "two-dragons",
            "ra ra-venomous-snake" => "venomous-snake",
            "ra ra-wyvern" => "wyvern",
            "ra ra-wolf-head" => "wolf-head",
            "ra ra-wolf-howl" => "wolf-howl",
        ),

        "tentacle" => array(

            "ra ra-spiked-tentacle" => "spiked",
            "ra ra-suckered-tentacle" => "suckered",
            "ra ra-tentacle" => "default",

        ),

        "key" => array(

            "ra ra-key" => "key",
            "ra ra-key-basic" => "key-basic",
            "ra ra-three-keys" => "three-keys",

        ),

        "Sac" => array(

            "ra ra-ammo-bag" => "ammo-bag",
            "ra ra-alligator-clip" => "alligator-clip",
            "ra ra-ball" => "ball",
            "ra ra-book" => "book",
            "ra ra-candle" => "candle",
            "ra ra-castle-flag" => "castle-flag",
            "ra ra-compass" => "compass",
            "ra ra-horseshoe" => "horseshoe",
            "ra ra-hourglass" => "hourglass",
            "ra ra-jigsaw-piece" => "jigsaw-piece",
            "ra ra-kettlebell" => "kettlebell",
            "ra ra-lantern-flame" => "lantern-flame",
            "ra ra-wrench" => "wrench",
            "ra ra-lit-candelabra" => "lit-candelabra",
            "ra ra-match" => "match",
            "ra ra-medical-pack" => "medical-pack",
            "ra ra-mirror" => "mirror",
            "ra ra-moon-sun" => "moon-sun",
            "ra ra-nails" => "nails",
            "ra ra-noose" => "noose",
            "ra ra-ocarina" => "ocarina",
            "ra ra-pawn" => "pawn",
            "ra ra-pill" => "pill",
            "ra ra-pills" => "pills",
            "ra ra-ping-pong" => "ping-pong",
            "ra ra-potion" => "potion",
            "ra ra-quill-ink" => "quill-ink",
            "ra ra-ringing-bell" => "ringing-bell",
            "ra ra-rune-stone" => "rune-stone",
            "ra ra-sheriff" => "sheriff",
            "ra ra-shotgun-shell" => "shotgun-shell",
            "ra ra-slash-ring" => "slash-ring",
            "ra ra-snorkel" => "snorkel",
            "ra ra-soccer-ball" => "soccer-ball",
            "ra ra-spray-can" => "spray",
            "ra ra-stopwatch" => "stopwatch",
            "ra ra-syringe" => "syringe",
            "ra ra-torch" => "torch",
            "ra ra-trophy" => "trophy",
            "ra ra-wooden-sign" => "wooden-sign",

            "ra ra-bottom-right" => "bottom-right",
            "ra ra-cancel" => "cancel",

        ),

        "cards" => array(

            "ra ra-clovers" => "clovers",
            "ra ra-clovers-card" => "clovers-card",
            "ra ra-diamonds" => "diamonds",
            "ra ra-diamonds-card" => "diamonds-card",
            "ra ra-hearts" => "hearts",
            "ra ra-hearts-card" => "hearts-card",
            "ra ra-spades" => "spades",
            "ra ra-spades-card" => "spades-card",
            "ra ra-suits" => "suits",
        ),

        "dice" => array(

            "ra ra-dice-one" => "one",
            "ra ra-dice-two" => "two",
            "ra ra-dice-three" => "three",
            "ra ra-dice-four" => "four",

            "ra ra-perspective-dice-one" => "perspective one",
            "ra ra-perspective-dice-two" => "perspective two",
            "ra ra-perspective-dice-three" => "perspective three",
            "ra ra-perspective-dice-four" => "perspective four",

            "ra ra-dice-five" => "five",
            "ra ra-perspective-dice-five" => "perspective five",

            "ra ra-dice-six" => "six",
            "ra ra-perspective-dice-six" => "perspective six",

            "ra ra-perspective-dice-random" => "random",

        ),

        "chess" => array(

            "ra ra-chessboard" => "chessboard",
            "glyphicon glyphicon-king" => "king",
            "glyphicon glyphicon-queen" => "queen",
            "glyphicon glyphicon-pawn" => "pawn",
            "glyphicon glyphicon-bishop" => "bishop",
            "glyphicon glyphicon-knight" => "knight",
            "glyphicon glyphicon-tower" => "tower",

        ),

        "nourriture" => array(

            "ra ra-acorn" => "acorn",
            "ra ra-apple" => "apple",
            "ra ra-beer" => "beer",
            "ra ra-brandy-bottle" => "brandy-bottle",
            "ra ra-carrot" => "carrot",
            "ra ra-cheese" => "cheese",
            "ra ra-chicken-leg" => "chicken-leg",
            "ra ra-coffee-mug" => "coffee-mug",
            "ra ra-crab-claw" => "crab-claw",
            "ra ra-egg" => "egg",
            "ra ra-egg-pod" => "egg-pod",
            "ra ra-eggplant" => "eggplant",
            "ra ra-honeycomb" => "honeycomb",
            "ra ra-ice-cube" => "ice-cube",
            "ra ra-knife-fork" => "knife-fork",
            "ra ra-meat" => "meat",
            "ra ra-roast-chicken" => "roast-chicken",
            "ra ra-toast" => "toast",

            "glyphicon glyphicon-ice-lolly" => "ice-lolly",
            "glyphicon glyphicon-ice-lolly-tasted" => "ice-lolly-tasted",
            "glyphicon glyphicon-cutlery" => "cutlery",
            "glyphicon glyphicon-baby-formula" => "baby-formula",
            "glyphicon glyphicon-apple" => "apple",
            "glyphicon glyphicon-oil" => "oil",
            "glyphicon glyphicon-grain" => "grain",
            "glyphicon glyphicon-menu-hamburger" => "menu-hamburger",

        ),

        "astrology" => array(

            "ra ra-aquarius" => "aquarius",
            "ra ra-aries" => "aries",
            "ra ra-cancer" => "cancer",
            "ra ra-capricorn" => "capricorn",
            "ra ra-gemini" => "gemini",
            "ra ra-libra" => "libra",
            "ra ra-leo" => "leo",
            "ra ra-pisces" => "pisces",
            "ra ra-sagittarius" => "sagittarius",
            "ra ra-scorpio" => "scorpio",
            "ra ra-taurus" => "taurus",
            "ra ra-virgo" => "virgo",
        ),

        "dangers" => array(

            "ra ra-acid" => "acid",
            "ra ra-arson" => "arson",
            "ra ra-biohazard" => "biohazard",
            "ra ra-blade-bite" => "blade-bite",
            "ra ra-blast" => "blast",
            "ra ra-blaster" => "blaster",
            "ra ra-bleeding-eye" => "bleeding-eye",
            "ra ra-bleeding-hearts" => "bleeding-hearts",
            "ra ra-bone-bite" => "bone-bite",
            "ra ra-burning-meteor" => "burning-meteor",
            "ra ra-crush" => "crush",
            "ra ra-decapitation" => "decapitation",
            "ra ra-fall-down" => "fall-down",
            "ra ra-fire" => "fire",
            "ra ra-food-chain" => "food-chain",
            "ra ra-focused-lightning" => "focused-lightning",
            "ra ra-heartburn" => "heartburn",
            "ra ra-poison-cloud" => "poison-cloud",
            "ra ra-tombstone" => "tombstone",
        ),

        "level" => array(

            "ra ra-level-two" => "two",
            "ra ra-level-three" => "three",
            "ra ra-level-four" => "four",
            "glyphicon glyphicon-level-up" => "up",
            "ra ra-level-two-advanced" => "two-advanced",
            "ra ra-level-three-advanced" => "three-advanced",
            "ra ra-level-four-advanced" => "four-advanced",

        ),

        "magie" => array(

            "ra ra-brain-freeze" => "brain-freeze",
            "ra ra-burning-book" => "burning-book",
            "ra ra-burning-embers" => "burning-embers",
            "ra ra-burning-eye" => "burning-eye",
            "ra ra-burst-blob" => "burst-blob",
            "ra ra-cold-heart" => "cold-heart",
            "ra ra-crystal-ball" => "crystal-ball",
            "ra ra-crystal-cluster" => "crystal-cluster",
            "ra ra-crystal-wand" => "crystal-wand",
            "ra ra-crystals" => "crystals",
            "ra ra-diamond" => "diamond",
            "ra ra-divert" => "divert",
            "ra ra-doubled" => "doubled",
            "ra ra-dragon-breath" => "dragon-breath",
            "ra ra-droplet" => "droplet",
            "ra ra-droplet-splash" => "droplet-splash",
            "ra ra-emerald" => "emerald",
            "ra ra-eyeball" => "eyeball",
            "ra ra-fairy-wand" => "fairy-wand",
            "ra ra-fire-breath" => "fire-breath",
            "ra ra-fire-ring" => "fire-ring",
            "ra ra-frostfire" => "frostfire",
            "ra ra-gem" => "gem",
            "ra ra-gem-pendant" => "gem-pendant",
            "ra ra-gloop" => "gloop",
            "ra ra-gold-bar" => "gold-bar",
            "ra ra-health" => "health",
            "ra ra-health-decrease" => "health-decrease",
            "ra ra-health-increase" => "health-increase",
            "ra ra-hospital-cross" => "hospital-cross",
            "ra ra-hydra-shot" => "hydra-shot",
            "ra ra-incense" => "incense",
            "ra ra-kaleidoscope" => "kaleidoscope",

            "ra ra-lightning" => "lightning",
            "ra ra-lightning-bolt" => "lightning-bolt",
            "ra ra-lightning-storm" => "lightning-storm",
            "ra ra-lightning-trio" => "lightning-trio",
            "ra ra-sapphire" => "sapphire",
            "ra ra-small-fire" => "small-fire",
            "ra ra-snowflake" => "snowflake",
            "ra ra-sun" => "sun",


            "ra ra-sunbeams" => "sunbeams",
            "ra ra-triforce" => "triforce",
            "ra ra-two-hearts" => "two-hearts",
            "ra ra-water-drop" => "water-drop",
        ),

        "landing" => array(

            "ra ra-hot-surface" => "hot-surface",
            "ra ra-lava" => "lava",
        ),

        "weapons-and-armor" => array(

            "ra ra-arcane-mask" => "arcane-mask",
            "ra ra-all-for-one" => "all-for-one",
            "ra ra-anvil" => "anvil",
            "ra ra-archer" => "archer",
            "ra ra-archery-target" => "archery-target",
            "ra ra-arena" => "arena",
            "ra ra-arrow-cluster" => "arrow-cluster",
            "ra ra-arrow-flights" => "arrow-flights",
            "ra ra-axe" => "axe",
            "ra ra-axe-swing" => "axe-swing",
            "ra ra-barbed-arrow" => "barbed-arrow",
            "ra ra-barrier" => "barrier",
            "ra ra-bat-sword" => "bat-sword",
            "ra ra-battered-axe" => "battered-axe",
            "ra ra-beam-wake" => "beam-wake",
            "ra ra-bear-trap" => "bear-trap",
            "ra ra-bolt-shield" => "bolt-shield",
            "ra ra-bomb-explosion" => "bomb-explosion",
            "ra ra-bombs" => "bombs",
            "ra ra-bone-knife" => "bone-knife",
            "ra ra-boomerang" => "boomerang",
            "ra ra-boot-stomp" => "boot-stomp",
            "ra ra-bowie-knife" => "bowie-knife",
            "ra ra-broadhead-arrow" => "broadhead-arrow",
            "ra ra-broken-bone" => "broken-bone",
            "ra ra-broken-shield" => "broken-shield",
            "ra ra-bullets" => "bullets",
            "ra ra-cannon-shot" => "cannon-shot",
            "ra ra-chemical-arrow" => "chemical-arrow",
            "ra ra-chain" => "chain",
            "ra ra-circular-saw" => "circular-saw",
            "ra ra-circular-shield" => "circular-shield",
            "ra ra-cluster-bomb" => "cluster-bomb",
            "ra ra-cracked-shield" => "cracked-shield",
            "ra ra-croc-sword" => "croc-sword",
            "ra ra-crossbow" => "crossbow",
            "ra ra-crossed-axes" => "crossed-axes",
            "ra ra-crossed-bones" => "crossed-bones",
            "ra ra-crossed-pistols" => "crossed-pistols",
            "ra ra-crossed-sabres" => "crossed-sabers",
            "ra ra-crossed-swords" => "crossed-swords",
            "ra ra-daggers" => "daggers",
            "ra ra-dervish-swords" => "dervish-swords",
            "ra ra-diving-dagger" => "diving-dagger",
            "ra ra-drill" => "drill",
            "ra ra-dripping-blade" => "dripping-blade",
            "ra ra-dripping-knife" => "dripping-knife",
            "ra ra-dripping-sword" => "dripping-sword",
            "ra ra-duel" => "duel",
            "ra ra-explosion" => "explosion",
            "ra ra-explosive-materials" => "explosive-materials",
            "ra ra-eye-shield" => "eye-shield",
            "ra ra-fire-bomb" => "fire-bomb",
            "ra ra-fire-shield" => "fire-shield",
            "ra ra-fireball-sword" => "fireball-sword",
            "ra ra-flaming-arrow" => "flaming-arrow",
            "ra ra-flaming-claw" => "flaming-claw",
            "ra ra-flaming-trident" => "flaming-trident",
            "ra ra-flat-hammer" => "flat-hammer",
            "ra ra-frozen-arrow" => "frozen-arrow",
            "ra ra-gavel" => "gavel",
            "ra ra-gear-hammer" => "gear-hammer",
            "ra ra-grappling-hook" => "grappling-hook",
            "ra ra-grenade" => "grenade",
            "ra ra-guillotine" => "guillotine",
            "ra ra-halberd" => "halberd",
            "ra ra-hammer" => "hammer",
            "ra ra-hammer-drop" => "hammer-drop",
            "ra ra-hand-saw" => "hand-saw",
            "ra ra-harpoon-trident" => "harpoon-trident",
            "ra ra-horns" => "horns",
            "ra ra-heavy-shield" => "heavy-shield",
            "ra ra-implosion" => "implosion",
            "ra ra-jetpack" => "jetpack",
            "ra ra-kitchen-knives" => "kitchen-knives",
            "ra ra-knife" => "knife",
            "ra ra-kunai" => "kunai",
            "ra ra-large-hammer" => "large-hammer",
            "ra ra-laser-blast" => "laser-blast",
            "ra ra-lightning-sword" => "lightning-sword",
            "ra ra-mass-driver" => "mass-driver",
            "ra ra-mp5" => "mp5",
            "ra ra-musket" => "musket",
            "ra ra-plain-dagger" => "plain-dagger",
            "ra ra-relic-blade" => "relic-blade",
            "ra ra-revolver" => "revolver",
            "ra ra-rifle" => "rifle",
            "ra ra-round-shield" => "round-shield",
            "ra ra-scythe" => "scythe",
            "ra ra-shield" => "shield",
            "ra ra-shuriken" => "shuriken",
            "ra ra-sickle" => "sickle",
            "ra ra-spear-head" => "spear-head",
            "ra ra-spikeball" => "spikeball",
            "ra ra-spiked-mace" => "spiked-mace",
            "ra ra-spinning-sword" => "spinning-sword",
            "ra ra-supersonic-arrow" => "supersonic-arrow",
            "ra ra-sword" => "sword",
            "ra ra-target-arrows" => "target-arrows",
            "ra ra-target-laser" => "target-laser",
            "ra ra-thorn-arrow" => "thorn-arrow",
            "ra ra-thorny-vine" => "thorny-vine",
            "ra ra-trident" => "trident",
            "ra ra-vest" => "vest",
            "ra ra-vine-whip" => "vine-whip",
            "ra ra-zebra-shield" => "zebra-shield",

        ),

        "symbol" => array(

            "ra ra-fire-symbol" => "fire",
            "ra ra-flame-symbol" => "flame",
            "ra ra-sun-symbol" => "sun",
        ),

        "emblem" => array(

            "ra ra-castle-emblem" => "castle",
            "ra ra-hive-emblem" => "hive",
            "ra ra-ocean-emblem" => "ocean",
            "ra ra-frost-emblem" => "frost",
            "ra ra-hand-emblem" => "hand",
            "ra ra-sprout-emblem" => "sprout",
            "ra ra-ship-emblem" => "ship",
        ),

        "wing" => array(

            "ra ra-angel-wings" => "angel-wings",
            "ra ra-batwings" => "batwings",
            "ra ra-dragon-wing" => "dragon",
            "ra ra-feather-wing" => "feather",
            "ra ra-feathered-wing" => "feathered",

        ),

        "skull" => array(

            "ra ra-skull" => "normal",
            "ra ra-broken-skull" => "broken",
            "ra ra-death-skull" => "death",
            "ra ra-desert-skull" => "desert",
            "ra ra-skull-trophy" => "trophy",
            "ra ra-monster-skull" => "monster",
        ),

        "rpg-icons" => array(

            "ra ra-ankh" => "ankh",
            "ra ra-anchor" => "anchor",
            "ra ra-bell" => "bell",
            "ra ra-bird-mask" => "bird-mask",
            "ra ra-bowling-pin" => "bowling-pin",
            "ra ra-bridge" => "bridge",
            "ra ra-campfire" => "campfire",
            "ra ra-candle-fire" => "candle-fire",
            "ra ra-capitol" => "capitol",
            "ra ra-crowned-heart" => "crowned-heart",
            "ra ra-cubes" => "cubes",
            "ra ra-cut-palm" => "cut-palm",
            "ra ra-cycle" => "cycle",
            "ra ra-demolish" => "demolish",
            "ra ra-fedora" => "fedora",
            "ra ra-fluffy-swirl" => "fluffy-swirl",
            "ra ra-footprint" => "footprint",
            "ra ra-forging" => "forging",
            "ra ra-forward" => "forward",
            "ra ra-glass-heart" => "glass-heart",
            "ra ra-groundbreaker" => "groundbreaker",
            "ra ra-heavy-fall" => "heavy-fall",
            "ra ra-help" => "help",
            "ra ra-hole-ladder" => "hole-ladder",
            "ra ra-hood" => "hood",
            "ra ra-horn-call" => "horn-call",
            "ra ra-interdiction" => "interdiction",
            "ra ra-locked-fortress" => "locked-fortress",
            "ra ra-meat-hook" => "meat-hook",
            "ra ra-metal-gate" => "metal-gate",
            "ra ra-mine-wagon" => "mine-wagon",
            "ra ra-mining-diamonds" => "mining-diamonds",
            "ra ra-mountains" => "mountains",
            "ra ra-nodular" => "nodular",
            "ra ra-omega" => "omega",
            "ra ra-on-target" => "on-target",
            "ra ra-ophiuchus" => "ophiuchus",
            "ra ra-overhead" => "overhead",
            "ra ra-overmind" => "overmind",
            "ra ra-pawprint" => "pawprint",
            "ra ra-podium" => "podium",
            "ra ra-pyramids" => "pyramids",
            "ra ra-radial-balance" => "radial-balance",
            "ra ra-reverse" => "reverse",
            "ra ra-scroll-unfurled" => "scroll-unfurled",
            "ra ra-shoe-prints" => "shoe-prints",
            "ra ra-shot-through-the-heart" => "shot-through-the-heart",
            "ra ra-shovel" => "shovel",
            "ra ra-sideswipe" => "sideswipe",
            "ra ra-site" => "site",
            "ra ra-spawn-node" => "spawn-node",
            "ra ra-splash" => "splash",
            "ra ra-targeted" => "targeted",
            "ra ra-tic-tac-toe" => "tic-tac-toe",
            "ra ra-tooth" => "tooth",
            "ra ra-trail" => "trail",
            "ra ra-turd" => "turd",
            "ra ra-uncertainty" => "uncertainty",
            "ra ra-underhand" => "underhand",
            "ra ra-x-mark" => "x-mark",

        ),

        "record-format" => array(

            "glyphicon glyphicon-sd-video" => "sd-video",
            "glyphicon glyphicon-hd-video" => "hd-video",
            "glyphicon glyphicon-subtitles" => "subtitles",
            "glyphicon glyphicon-sound-stereo" => "sound-stereo",
            "glyphicon glyphicon-sound-dolby" => "sound-dolby",
            "glyphicon glyphicon-sound-5-1" => "sound-5-1",
            "glyphicon glyphicon-sound-6-1" => "sound-6-1",
            "glyphicon glyphicon-sound-7-1" => "sound-7-1",


        ),

        "record-player" => array(

            "glyphicon glyphicon-step-backward" => "step-backward",
            "glyphicon glyphicon-fast-backward" => "fast-backward",
            "glyphicon glyphicon-backward" => "backward",
            "glyphicon glyphicon-play" => "play",
            "glyphicon glyphicon-pause" => "pause",
            "glyphicon glyphicon-stop" => "stop",
            "glyphicon glyphicon-forward" => "forward",
            "glyphicon glyphicon-fast-forward" => "fast-forward",
            "glyphicon glyphicon-step-forward" => "step-forward",
            "glyphicon glyphicon-eject" => "eject",

        ),

        "Arrow" => array(

            "glyphicon glyphicon-arrow-left" => "left",
            "glyphicon glyphicon-arrow-right" => "right",
            "glyphicon glyphicon-arrow-up" => "up",
            "glyphicon glyphicon-arrow-down" => "down",

            "glyphicon glyphicon-circle-arrow-left" => "circle-left",
            "glyphicon glyphicon-circle-arrow-right" => "circle-right",
            "glyphicon glyphicon-circle-arrow-up" => "circle-up",
            "glyphicon glyphicon-circle-arrow-down" => "circle-down",

            "glyphicon glyphicon-chevron-left" => "chevron-left",
            "glyphicon glyphicon-chevron-right" => "chevron-right",
            "glyphicon glyphicon-chevron-up" => "chevron-up",
            "glyphicon glyphicon-chevron-down" => "chevron-down",

            "glyphicon glyphicon-triangle-left" => "triangle-left",
            "glyphicon glyphicon-triangle-right" => "triangle-right",
            "glyphicon glyphicon-triangle-top" => "triangle-top",
            "glyphicon glyphicon-triangle-bottom" => "triangle-bottom",

            "glyphicon glyphicon-menu-left" => "menu-left",
            "glyphicon glyphicon-menu-right" => "menu-right",
            "glyphicon glyphicon-menu-up" => "menu-up",
            "glyphicon glyphicon-menu-down" => "menu-down",

        ),

        "Floppy" => array(

            "glyphicon glyphicon-floppy-disk" => "disk",
            "glyphicon glyphicon-floppy-saved" => "saved",
            "glyphicon glyphicon-floppy-save" => "save",
            "glyphicon glyphicon-floppy-open" => "open",

            "glyphicon glyphicon-record" => "record",
            "glyphicon glyphicon-saved" => "saved",
            "glyphicon glyphicon-save" => "save",
            "glyphicon glyphicon-open" => "open",

            "glyphicon glyphicon-copy" => "copy",
            "glyphicon glyphicon-paste" => "paste",
            "glyphicon glyphicon-save-file" => "save",
            "glyphicon glyphicon-open-file" => "open",

            "glyphicon glyphicon-floppy-remove" => "remove",
            "glyphicon glyphicon-import" => "import",
            "glyphicon glyphicon-export" => "export",
            "glyphicon glyphicon-send" => "send",
        ),

        "Sort" => array(

            "glyphicon glyphicon-sort-by-alphabet" => "alphabet",
            "glyphicon glyphicon-sort-by-order" => "order",
            "glyphicon glyphicon-sort-by-attributes" => "attributes",
            "glyphicon glyphicon-sort" => "default",

            "glyphicon glyphicon-sort-by-alphabet-alt" => "alphabet-alt",
            "glyphicon glyphicon-sort-by-order-alt" => "order-alt",
            "glyphicon glyphicon-sort-by-attributes-alt" => "attributes-alt",

        ),

        "editor" => array(

            "glyphicon glyphicon-font" => "font",
            "glyphicon glyphicon-bold" => "bold",
            "glyphicon glyphicon-italic" => "italic",

        ),

        "align" => array(

            "glyphicon glyphicon-align-left" => "left",
            "glyphicon glyphicon-align-center" => "center",
            "glyphicon glyphicon-align-right" => "right",
            "glyphicon glyphicon-align-justify" => "justify",

        ),

        "text" => array(

            "glyphicon glyphicon-text-height" => "text-height",
            "glyphicon glyphicon-text-width" => "text-width",
            "glyphicon glyphicon-text-size" => "text-size",
            "glyphicon glyphicon-text-color" => "text-color",
            "glyphicon glyphicon-text-background" => "text-background",
        ),

        "circle" => array(

            "glyphicon glyphicon-remove-circle" => "remove",
            "glyphicon glyphicon-ok-circle" => "ok",
            "glyphicon glyphicon-ban-circle" => "ban",
            "glyphicon glyphicon-play-circle" => "play",
            "ra ra-circle-of-circles" => "circles",

        ),

        "money" => array(

            "glyphicon glyphicon-euro" => "euro",
            "glyphicon glyphicon-eur" => "eur",
            "glyphicon glyphicon-bitcoin" => "bitcoin",
            "glyphicon glyphicon-btc" => "btc",
            "glyphicon glyphicon-xbt" => "xbt",
            "glyphicon glyphicon-yen" => "yen",
            "glyphicon glyphicon-jpy" => "jpy",
            "glyphicon glyphicon-ruble" => "ruble",
            "glyphicon glyphicon-rub" => "rub",
            "glyphicon glyphicon-usd" => "usd",
            "glyphicon glyphicon-gbp" => "gbp",
        ),

        "object-align" => array(

            "glyphicon glyphicon-object-align-top" => "top",
            "glyphicon glyphicon-object-align-bottom" => "bottom",
            "glyphicon glyphicon-object-align-horizontal" => "horizontal",
            "glyphicon glyphicon-object-align-left" => "left",
            "glyphicon glyphicon-object-align-vertical" => "vertical",
            "glyphicon glyphicon-object-align-right" => "right",
        ),

        "sign" => array(

            "glyphicon glyphicon-plus-sign" => "plus",
            "glyphicon glyphicon-minus-sign" => "minus",
            "glyphicon glyphicon-remove-sign" => "remove",
            "glyphicon glyphicon-ok-sign" => "ok",
            "glyphicon glyphicon-question-sign" => "question",
            "glyphicon glyphicon-info-sign" => "info",
            "glyphicon glyphicon-exclamation-sign" => "exclamation",
            "glyphicon glyphicon-warning-sign" => "warning",

        ),

        "volume" => array(

            "glyphicon glyphicon-headphones" => "headphones",
            "glyphicon glyphicon-volume-off" => "off",
            "glyphicon glyphicon-volume-down" => "down",
            "glyphicon glyphicon-volume-up" => "up",
        ),

        "star" => array(

            "glyphicon glyphicon-star" => "star",
            "glyphicon glyphicon-star-empty" => "star-empty",
        ),

        "th" => array(

            "glyphicon glyphicon-th-large" => "th-large",
            "glyphicon glyphicon-th" => "th",
            "glyphicon glyphicon-th-list" => "th-list",
        ),

        "zoom" => array(

            "glyphicon glyphicon-zoom-in" => "zoom-in",
            "glyphicon glyphicon-zoom-out" => "zoom-out",
        ),

        "log" => array(

            "glyphicon glyphicon-log-in" => "log-in",
            "glyphicon glyphicon-log-out" => "log-out",
        ),

        "glyphicon" => array(

            "glyphicon glyphicon-asterisk" => "asterisk",
            "glyphicon glyphicon-plus" => "plus",
            "glyphicon glyphicon-minus" => "minus",
            "glyphicon glyphicon-cloud" => "cloud",
            "glyphicon glyphicon-envelope" => "envelope",
            "glyphicon glyphicon-pencil" => "pencil",
            "glyphicon glyphicon-glass" => "glass",
            "glyphicon glyphicon-music" => "music",
            "glyphicon glyphicon-search" => "search",
            "glyphicon glyphicon-heart" => "heart",
            "glyphicon glyphicon-user" => "user",
            "glyphicon glyphicon-film" => "film",
            "glyphicon glyphicon-ok" => "ok",
            "glyphicon glyphicon-remove" => "remove",
            "glyphicon glyphicon-off" => "off",
            "glyphicon glyphicon-signal" => "signal",
            "glyphicon glyphicon-cog" => "cog",
            "glyphicon glyphicon-trash" => "trash",
            "glyphicon glyphicon-home" => "home",
            "glyphicon glyphicon-file" => "file",
            "glyphicon glyphicon-time" => "time",
            "glyphicon glyphicon-road" => "road",
            "glyphicon glyphicon-download-alt" => "download-alt",
            "glyphicon glyphicon-download" => "download",
            "glyphicon glyphicon-upload" => "upload",
            "glyphicon glyphicon-inbox" => "inbox",
            "glyphicon glyphicon-repeat" => "repeat",
            "glyphicon glyphicon-refresh" => "refresh",
            "glyphicon glyphicon-list-alt" => "list-alt",
            "glyphicon glyphicon-lock" => "lock",
            "glyphicon glyphicon-flag" => "flag",
            "glyphicon glyphicon-qrcode" => "qrcode",
            "glyphicon glyphicon-barcode" => "barcode",
            "glyphicon glyphicon-tag" => "tag",
            "glyphicon glyphicon-tags" => "tags",
            "glyphicon glyphicon-book" => "book",
            "glyphicon glyphicon-bookmark" => "bookmark",
            "glyphicon glyphicon-print" => "print",
            "glyphicon glyphicon-camera" => "camera",
            "glyphicon glyphicon-list" => "list",
            "glyphicon glyphicon-indent-left" => "indent-left",
            "glyphicon glyphicon-indent-right" => "indent-right",
            "glyphicon glyphicon-facetime-video" => "facetime-video",
            "glyphicon glyphicon-picture" => "picture",
            "glyphicon glyphicon-map-marker" => "map-marker",
            "glyphicon glyphicon-adjust" => "adjust",
            "glyphicon glyphicon-tint" => "tint",
            "glyphicon glyphicon-edit" => "edit",
            "glyphicon glyphicon-share" => "share",
            "glyphicon glyphicon-check" => "check",
            "glyphicon glyphicon-move" => "move",
            "glyphicon glyphicon-screenshot" => "screenshot",

            "glyphicon glyphicon-share-alt" => "share-alt",
            "glyphicon glyphicon-resize-full" => "resize-full",
            "glyphicon glyphicon-resize-small" => "resize-small",
            "glyphicon glyphicon-gift" => "gift",
            "glyphicon glyphicon-fire" => "fire",
            "glyphicon glyphicon-eye-open" => "eye-open",
            "glyphicon glyphicon-eye-close" => "eye-close",
            "glyphicon glyphicon-plane" => "plane",
            "glyphicon glyphicon-calendar" => "calendar",
            "glyphicon glyphicon-random" => "random",
            "glyphicon glyphicon-comment" => "comment",
            "glyphicon glyphicon-magnet" => "magnet",
            "glyphicon glyphicon-retweet" => "retweet",
            "glyphicon glyphicon-shopping-cart" => "shopping-cart",
            "glyphicon glyphicon-folder-close" => "folder-close",
            "glyphicon glyphicon-folder-open" => "folder-open",
            "glyphicon glyphicon-resize-vertical" => "resize-vertical",
            "glyphicon glyphicon-resize-horizontal" => "resize-horizontal",
            "glyphicon glyphicon-hdd" => "hdd",
            "glyphicon glyphicon-bullhorn" => "bullhorn",
            "glyphicon glyphicon-bell" => "bell",
            "glyphicon glyphicon-certificate" => "certificate",
            "glyphicon glyphicon-globe" => "globe",
            "glyphicon glyphicon-wrench" => "wrench",
            "glyphicon glyphicon-tasks" => "tasks",
            "glyphicon glyphicon-filter" => "filter",
            "glyphicon glyphicon-briefcase" => "briefcase",
            "glyphicon glyphicon-fullscreen" => "fullscreen",
            "glyphicon glyphicon-dashboard" => "dashboard",
            "glyphicon glyphicon-paperclip" => "paperclip",
            "glyphicon glyphicon-heart-empty" => "heart-empty",
            "glyphicon glyphicon-link" => "link",
            "glyphicon glyphicon-phone" => "phone",
            "glyphicon glyphicon-pushpin" => "pushpin",
            "glyphicon glyphicon-unchecked" => "unchecked",
            "glyphicon glyphicon-expand" => "expand",
            "glyphicon glyphicon-collapse-down" => "collapse-down",
            "glyphicon glyphicon-collapse-up" => "collapse-up",
            "glyphicon glyphicon-flash" => "flash",
            "glyphicon glyphicon-new-window" => "new-window",
            "glyphicon glyphicon-credit-card" => "credit-card",
            "glyphicon glyphicon-transfer" => "transfer",
            "glyphicon glyphicon-header" => "header",
            "glyphicon glyphicon-compressed" => "compressed",
            "glyphicon glyphicon-earphone" => "earphone",
            "glyphicon glyphicon-phone-alt" => "phone-alt",
            "glyphicon glyphicon-stats" => "stats",
            "glyphicon glyphicon-copyright-mark" => "copyright-mark",
            "glyphicon glyphicon-registration-mark" => "registration-mark",
            "glyphicon glyphicon-cloud-download" => "cloud-download",
            "glyphicon glyphicon-cloud-upload" => "cloud-upload",
            "glyphicon glyphicon-cd" => "cd",
            "glyphicon glyphicon-alert" => "alert",
            "glyphicon glyphicon-equalizer" => "equalizer",
            "glyphicon glyphicon-tent" => "tent",
            "glyphicon glyphicon-blackboard" => "blackboard",
            "glyphicon glyphicon-bed" => "bed",
            "glyphicon glyphicon-erase" => "erase",
            "glyphicon glyphicon-hourglass" => "hourglass",
            "glyphicon glyphicon-lamp" => "lamp",
            "glyphicon glyphicon-duplicate" => "duplicate",
            "glyphicon glyphicon-piggy-bank" => "piggy-bank",
            "glyphicon glyphicon-scissors" => "scissors",
            "glyphicon glyphicon-scale" => "scale",
            "glyphicon glyphicon-education" => "education",
            "glyphicon glyphicon-option-horizontal" => "option-horizontal",
            "glyphicon glyphicon-option-vertical" => "option-vertical",
            "glyphicon glyphicon-modal-window" => "modal-window",
            "glyphicon glyphicon-sunglasses" => "sunglasses",
            "glyphicon glyphicon-console" => "console",
            "glyphicon glyphicon-superscript" => "superscript",
            "glyphicon glyphicon-subscript" => "subscript",
        ),
    );

}