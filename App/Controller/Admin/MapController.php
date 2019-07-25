<?php


namespace App\Controller\Admin;


use App\Model\Entity\Game\Item\ItemEntity;
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

    private function formMap($data)
    {
        $form = new Form($data);

        if($this->Item instanceof ItemTable)
        {
            $terrains = $this->Item->itemListing(array('terrain')) ; //request($statementT);
            $items = $this->Item->itemListing(array_merge(ItemEntity::type_arr['batiment'] , array('quest') )); //request($statementE);

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

    public function index(){

        if ($this->MapService instanceof MapService) {
            $this->alentours = $this->MapService->arround(0,0, 4);
        }
    }

    /**
     *
     */
    public function edit(){

        $x = Get::getInstance()->val('x');
        $y = Get::getInstance()->val('y');

        if ($this->MapService instanceof MapService) {
            $this->alentours = $this->MapService->arround($x,$y, 4);
        }

        if(Post::submited("post"))
        {
            $ter = $this->Item->find(Post::getInstance()->val('terrain'));
            $itm = $this->Item->find(Post::getInstance()->val('install'));
            $map = $this->Map->find(Post::getInstance()->val('id'));

            $map = $this->MapService->amorce($map,$ter);
            $itm->record = true ;

            $this->MapService->install($map,$itm);

            FlashBuilder::create("map edité","success");
            Redirect::reload();
        }

        $this->form = $this->formMap( $this->alentours[$x][$y] ?? GlobalRequest::getInstance()->content() );
    }

}