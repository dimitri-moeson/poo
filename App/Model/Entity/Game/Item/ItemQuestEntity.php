<?php
namespace App\Model\Entity\Game\Item;

use App\Model\Entity\Game\CraftTrait;
use \App\Model\Entity\Game\DataTrait ;
use App\Model\Entity\Journal;

/**
 * Class ItemQuestEntity
 * @package App\Model\Entity\Game\Item
 */
class ItemQuestEntity extends ItemEntity
{
    /**  */
    use DataTrait ;
    use CraftTrait ;

    /** @var  */
    private $failText ;

    /** @var  */
    private $successText ;

    /** @var bool  */
    private $complete = array() ;
    private $recolte = array() ;
    private $count = array() ;

    /**
     * @param $cible
     * @param int $count
     */
    public function ajoute($cible,$count = 1){

        $key = array_search($cible,$this->cibles);

        $this->count[$key] = $count;
        $this->recolte[$key] = 0 ;
        $this->complete[$key] = false ;
    }

    /**
     *
     */
    public function boot(){

        foreach($this->getRecette() as $key => $compo){

            $this->recolte[$key] = 0 ;
            $this->complete[$key] = false ;

        }

    }

    public function realise($cible){

        if(in_array($cible,$this->cibles)){
            $key = array_search($cible, $this->cibles);

            if($this->recolte[$key] <  $this->count[$key]) {

                Journal::getInstance()->add("Progression de la quete<br/>");

                $this->recolte[$key]++;

                if ($this->recolte[$key] == $this->count[$key]) {

                    Journal::getInstance()->add("etape de quete accompli<br/>");
                    $this->complete[$key] = true;
                }
            }
        }

    }

    /**
     * @return bool
     */
    public function complete(){

        if(in_array(false,$this->complete)) {
            Journal::getInstance()->add("cette quete est en  cours.<br/>");
            return false;
        }

        Journal::getInstance()->add("cette quete est complete. allez la rendre");
        return true ;
    }

    /**
     * ItemEntityQuest constructor.
     * @param $name
     * @param $desc
     * @param $type
     */
    public function __construct(/*$name,$desc,$type*/)
    {
        /*$this->setName($name);
        $this->setDescription($desc);
        $this->setType($type);*/
    }

    /**
     * @param mixed $failText
     * @return ItemEntityQuest
     */
    public function setFailText($failText)
    {
        $this->failText = $failText;
        return $this;
    }

    /**
     * @param mixed $successText
     * @return ItemEntityQuest
     */
    public function setSuccessText($successText)
    {
        $this->successText = $successText;
        return $this;
    }
}