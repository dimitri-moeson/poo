<?php


namespace App\Controller\Game;


use App;
use App\Model\Table\Game\Item\ItemTable;
use Core\HTML\Env\Post;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Request\Request;
use Core\Session\FlashBuilder;

class WikiController extends AppController
{
    /**
     * WikiController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Item\Item");
    }

    /**
     * @param null $type
     */
    public function index($type = null )
    {
        if(!is_null($type))
        {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
        }
        else
        {
            if($this->Item instanceof ItemTable )
                $this->posts = $this->Item->all();// findBy([],array("type" => "asc"));
            $this->type = null ;
        }
        Render::getInstance()->setView("Game/Wiki/Home"); // , compact('posts','categories'));
    }

    /**
     * @param $id
     */
    public function single($id){

        if(!is_null($id))
        {
            $this->post = $this->Item->find($id);
            if (!$this->post) $this->notFound("single item");

            $type = $this->post->type;
        }

        Header::getInstance()->setTitle($this->post->titre);

        if(!is_null($type))
        {
            $this->posts = $this->Item->typeListing([$type]);
            $this->type = $type;
        }
        else
        {
            $this->posts = $this->Item->all();
            $this->type = null ;
        }

        Render::getInstance()->setView("Game/Wiki/Single"); // , compact('post','categories','success','form'));
    }
}