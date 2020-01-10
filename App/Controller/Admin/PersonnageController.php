<?php


namespace App\Controller\Admin;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Service\PersonnageService;
use App\View\Form\PersonnageForm;
use Core\HTML\Env\Post;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class PersonnageController extends AppController
{
    /**
     * PersonnageController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("User");
        $this->loadModel("Game\Item\Item");
        $this->loadModel("Game\Inventaire\Inventaire");
        $this->loadModel("Game\Personnage\Personnage");

        $this->loadService("Personnage");
   }

    /**
     *
     */
    public function index()
    {
        $this->personnages = $this->Personnage->all();
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $post = $this->Personnage->find(Post::getInstance()->val('id'));
                if (!$post) $this->notFound("delete perso");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Personnage->archive(Post::getInstance()->val('id'))) {

                    FlashBuilder::create("perso suppr","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("personnage")
                        ->send();
                }
            }
        }
    }

    /**
     * @param $id
     */
    public function single($id)
    {
        if(Post::getInstance()->submit()) {

            $id_status = Post::getInstance()->val("status") ?? 0 ;

            $datas = array(

                "name" => Post::getInstance()->val("name"),
                "description" => Post::getInstance()->val("description"),
                "vie" => Post::getInstance()->val("vie"),
                "sexe" => Post::getInstance()->val("sexe"),
                "type" => Post::getInstance()->val("type"),
                "faction_id" => Post::getInstance()->val("faction_id"),
                "race_id" => Post::getInstance()->val("race_id"),
                "status" => ItemEntity::$categorie_arr["status"][$id_status]
            );

            if($this->Personnage->update($id, $datas ))
            {
                foreach(Post::getInstance()->val("stats") as $itm_id => $val )
                {
                    $dts = array(

                        "child_id" => $itm_id,
                        "parent_id" => $id,
                        "rubrique" => PersonnageService::RUB_PERSO,
                        "type" => PersonnageService::TYPE_STAT,
                    );

                    $inventaire = $this->Inventaire->findOneBy( $dts );

                    $edit =  array("val" => $val);

                    if($inventaire !== false)
                    {
                        $this->Inventaire->update($inventaire->id,$edit);
                    }
                    else
                    {
                        $this->Inventaire->create( array_merge( $dts , $edit ));
                    }
                }
            }
        }

        if(!is_null($id))
        {
            $this->post = $this->Personnage->find($id);
            if (!$this->post) $this->notFound("single perso");
        }

        if ($this->PersonnageService instanceof PersonnageService)
        {
            if($this->post->user_id != null)
            {
                $this->post = $this->PersonnageService->restor($this->post);
            }
        }

        $this->categories = $this->Personnage->list('id','nom');

        Header::getInstance()->setTitle($this->post->getName());

        $classes =  $this->Item->typeListing(ItemEntity::$type_arr["classe"]) ;
        $factions =  $this->Item->typeListing(["faction"]) ;
        $races =  $this->Item->typeListing(["race"]) ;

        $this->form = PersonnageForm::admin_perso($this->post,$classes,$factions,$races);
    }
}