<?php


namespace App\Controller\Game;


use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service\MovementService;
use Core\HTML\Env\Post;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class MovementController extends AppController
{
    /**
     * MovementController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadService("Movement");
        $this->loadService("Map");

        if ($this->MovementService instanceof MovementService)
            $this->MovementService->setPersonnage($this->legolas);
    }

    private function getMove($coords){

        $select = explode("|", $coords );
        
        return array(

            'x' => ($select[1] == "e" ? 1 : ($select[1] == "w" ? -1 : 0)),
            'y' => ($select[0] == "s" ? 1 : ($select[0] == "n" ? -1 : 0)),
        );
    }

    /**
     *
     */
    public function index()
    {
        if (Post::getInstance()->has('move')) {

            if ($this->legolas instanceof PersonnageEntity) {

                $select = explode("|", Post::getInstance()->val("coordonnees"));

                $coord = array(

                    'x' => $this->legolas->getPosition()->x + ($select[1] == "e" ? 1 : ($select[1] == "w" ? -1 : 0)),
                    'y' => $this->legolas->getPosition()->y + ($select[0] == "s" ? 1 : ($select[0] == "n" ? -1 : 0)),

                );

                $pos = $this->MapService->search($coord);

                if ($this->MovementService instanceof MovementService) {
                   if( $this->MovementService->move($pos)) {

                       FlashBuilder::create($this->legolas->getName() . " se deplace " . print_r($coord, 1), "success");
                       Redirect::getInstance()->setAct("index")->setCtl("default")->setDom("game")->setSlg("move")->send();
                   }
                }
            }
        }
    }

}