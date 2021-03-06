<?php


namespace App\Controller\Game;

use App\Model\Object\Game\Combat\Defi;
use App\Model\Heritage\Game\Item\ItemMonstreEntity;
use App\Model\Service\EquipementService;
use App\Model\Service\MonstreService;
use Core\Redirect\Redirect;
use Core\Session\FlashBuilder;

class ArenaController extends AppController
{
    /**
     * ArenaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if($this->EquipementService instanceof EquipementService)
        {
            $this->EquipementService->setPersonnage($this->legolas);
        }

        $this->loadService("Monstre");
    }

    /**
     * @param $challenger
     */
    public function defi($challenger)
    {
        if($this->MonstreService instanceof MonstreService )
        {
            $strum = $this->MonstreService->getFightable($challenger);

            if($strum instanceof ItemMonstreEntity)
            {
                $strum->setPosition($this->legolas->getPosition());

                $defi = new Defi(array($this->legolas, $strum));

                $_SESSION['defi'] = serialize($defi);
                FlashBuilder::create( "combat lancé","success");

                Redirect::getInstance()->setCtl('fight')->setDom("game")->setAct("index")->send();
            }
        }
    }
}