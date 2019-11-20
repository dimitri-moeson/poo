<?php


namespace App\Model\Comportement;


use App\Model\Entity\Game\Inventaire\InventaireRecetteEntity;

trait CraftTrait
{
    /** @var InventaireRecetteEntity */
    public $recette ;

    /**
     * @return mixed
     */
    public function getRecette()
    {
        if(parent::getRecette())
            return $this->recette;
    }

    /**
     * @param mixed $recette
     */
    public function setRecette(InventaireRecetteEntity $recette)
    {
        $this->recette = $recette;
        return $this;
    }
}