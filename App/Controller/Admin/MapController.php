<?php


namespace App\Controller\Admin;


use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Map\MapEntity;
use App\Model\Service\MapService;
use App\Model\Table\Game\Item\ItemTable;
use App\View\Form\MapForm;
use Core\Database\QueryBuilder;
use Core\HTML\Env\Get;
use Core\HTML\Env\GlobalRequest;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class MapController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Map\Map");
        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Inventaire\Inventaire");
        $this->loadService("Map");
    }

    /**
     * @param $data
     * @return Form
     */
    private function formMap($data)
    {
        $form = new Form($data);

        if($this->Item instanceof ItemTable)
        {
            $terrains = $this->Item->itemListing(array('terrain')) ; //request($statementT);

            $install_arr = ItemEntity::getPlaceTypeArray(); /*array_merge(ItemEntity::type_arr['batiment'] , array('quest') );*/

            //print_r($install_arr);

            $items = $this->Item->typeListing($install_arr); //request($statementE);

            $form->input("id",array("type" => "number","readonly"=>"true"))
                ->input("x",array("type" => "number"))
                ->input("y",array("type" => "number"))
                ->addInput("terrain_id", MapForm::selectTerrain(@$data->terrain_id,$terrains))
                ->addInput("child_id", MapForm::selectInstall(@$data->child_id,$items))
                ->submit("Enregistrer")
            ;
        }

        return $form ;
    }

    /**
     *
     */
    public function index(){

        if ($this->MapService instanceof MapService) {
            $this->alentours = $this->MapService->arround(0,0, 4);
        }
    }

    /**
     *
     */
    public function edit(){

        $x = Get::getInstance()->val('x') ?? 0;
        $y = Get::getInstance()->val('y') ?? 0;

        if ($this->MapService instanceof MapService) {
            $this->alentours = $this->MapService->arround($x,$y, 4);
        }

        if(Post::getInstance()->submit())
        {
            $ter = $this->Item->find(Post::getInstance()->val('terrain'));
            $itm = $this->Item->find(Post::getInstance()->val('install'));

            if(Post::getInstance()->has('id')) {
                $map = $this->Map->find(Post::getInstance()->val('id'));
            }else {
                $map = MapEntity::init($x, $y, $ter);
            }
            $map = $this->MapService->amorce($map,$ter);
            $itm->record = true ;

            $this->MapService->install($map,$itm);

            FlashBuilder::create("map edité","success");
            Redirect::getInstance()->reload();
        }

        $this->form = $this->formMap( $this->alentours[$x][$y] ?? GlobalRequest::getInstance()->content() );
    }

}