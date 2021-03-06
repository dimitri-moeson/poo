<?php


namespace App\Model\Comportement;


use App\Model\Entity\Game\Item\ItemEntity;

trait DataTrait
{
    /** @var  */
    public $name;

    /** @var  */
    public $description ;

    /** @var  */
    public $img ;

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * on cherche l'item enregistré de l'objet...
     * @var ItemEntity
     */
    public $type ;

    /**
     * @param mixed $description
     * @return DataTrait
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $name
     * @return DataTrait
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $img
     * @return DataTrait
     */
    public function setImg($img)
    {
        $this->img = $img;
        return $this;
    }

    /**
     * @param mixed $type
     * @return DataTrait
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name ?? "Personnage";
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
}