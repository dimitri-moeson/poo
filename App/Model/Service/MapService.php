<?php


namespace App\Model\Service;


use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Map\MapEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;
use App\Model\Table\Game\Map\MapTable;
use Core\Debugger\Debugger;
use Core\Render\Url;

class MapService extends Service
{
    /**
     * @var PersonnageEntity
     */
    private $personnage;

    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Personnage\Personnage");
        $this->loadModel("Game\Map\Map");
        $this->loadModel("Game\Inventaire\Inventaire");
    }

    /**
     * @param PersonnageEntity $personnage
     */
    public function setPersonnage(PersonnageEntity $personnage): void
    {
        $this->personnage = $personnage;
    }

    public function amorce(MapEntity $map , ItemEntity $terrain = null){

        $map->record = true ;
        $map->terrain = $terrain;
        $map->id = $this->save($map);

        return $map ;
    }

    public function search( Array $coord , ItemEntity $terrain = null ){

        $map = $this->MapBase->search( $coord['x'] , $coord['y'] );

        if(!$map)
        {
            $map = MapEntity::init( $coord['x'] , $coord['y'] , $terrain );
        }

        $map = $this->amorce($map , $terrain);

        return $map ;
    }

    function install(MapEntity $map, ItemEntity $item)
    {
        if (isset($item->record) && $item->record === true) {

            $datas = array(

                "parent_id" => $map->id,
                "child_id" => $item->id,
                "type" => $item->type,
                "rubrique" => "localisation",
                "val" => 1,
                "caract" => ""
            );

            //Debugger::getInstance()->add($datas);

            if ($this->InventaireBase instanceof InventaireTable) {
                /*if (!isset($item->val) || !is_null($item->val) || $item->val == 0 || empty($item->val))
                    $this->InventaireBase->archive($item->inventaire_id);
                else*/
                if (!is_null($item->inventaire_id))
                    $this->InventaireBase->update($item->inventaire_id, $datas);
                else
                    $this->InventaireBase->create($datas);
            }
        }
    }

    /**
     * @param PersonnageEntity $personnage
     */
    function save(MapEntity $map)
    {
        $datas = array(

            'x' => $map->x,
            'y' => $map->y,
        );

        if(isset($map->terrain) && !is_null($map->terrain->id)){

            $datas['terrain_id'] = $map->terrain->id ?? null ;
        }

        if($this->MapBase instanceof MapTable) {
            //echo "instanceof OK - ";
            if (isset($map->id) && !is_null($map->id)) {
                //echo "update launch";
                $this->MapBase->update($map->id, $datas);
                return $map->id;
            } else {
                //echo "create launch";
                $this->MapBase->create($datas);
                return App::getInstance()->getDb()->lasInsertId();
            }
        }

    }

    function arround(Int $arg_x = null,Int $arg_y = null , Int $portee = 2)
    {
        $x = $arg_x ?? $this->personnage->getPosition()->x;
        $y = $arg_y ?? $this->personnage->getPosition()->y;

        $max_x = $x + $portee;
        $min_x = $x - $portee;

        $max_y = $y + $portee;
        $min_y = $y - $portee;

        $_maps = array();

        if ($this->MapBase instanceof MapTable)
        {
            $maps = $this->MapBase->getZone($min_x, $max_x, $min_y, $max_y);

            foreach ($maps as $map) {

                $_maps[$map->x][$map->y] = $map;
            }
        }
        return $_maps ;
    }

    function adminPlace($place = null , $a = null , $b = null )
    {

        $link = Url::generate("edit","map","admin")->setParams(array("x" => $a, "y" => $b ));

        if(!is_null($place))
        {
            if (isset($place->name))
            {
                return "<a title='" . $place->type . "/" . $place->structure . "/" . $place->name . "' href='".$link."'>" .
                    "<i class='" . $place->img . "'></i>" .
                    "</a>";
            }
            else {

                return "<a href='".$link."'>[+]</a>";
            }
        }
            return "<a href='".$link."'>[x]</a>";
    }

    function place($place = null )
    {
        if(isset($place->name))
        {
            $info = false ;

            if($place->x == $this->personnage->getPosition()->x){
                if($place->y == $this->personnage->getPosition()->y){
                    $info = true ;
                }
            }

            if($info)
            {
                if ($place->structure == 'mairie'){

                    $link = "?p=test.quest&id=".$place->i_id;

                }
                if ($place->structure == 'ecole'){

                    $link = "?p=test.apprentissage&id=".$place->i_id;

                }
                if ($place->structure == 'forge'){

                    $link = "?p=test.craft&id=".$place->i_id;

                }
                if ($place->structure == 'arene'){

                    $link = "?p=test.arene&id=".$place->i_id;

                }
                if ($place->structure == 'quest'){

                    /** @var possibilité d'avoir plusieurs quetes au meme emplacement */
                    $link = "?p=test.quest&id=".$place->i_id;

                }

                return "<a href='".$link."' title='" . $place->type ."/" . $place->structure . "'><i class='" . $place->img . "'></i></a>";
            }
            else {
                return "<a title='" . $place->type . "/" . $place->structure . "/" . $place->name . "'><i class='" . $place->img . "'></i></a>";
            }
        }
        else
            return "[]";

    }
}