<?php
namespace App\Model\Entity\Game\Personnage ;

use App\Model\Comportement\DataTrait;
use App\Model\Comportement\CombattantTrait;
use App\Model\Comportement\PersonnageTrait;

use App\Model\Entity\UserEntity;
use Core\Model\Entity\Entity;

use App;

/**
 * App PersonnageEntity
 * @property  name
 */
class PersonnageEntity extends Entity {

    use DataTrait;
    use CombattantTrait;
    use PersonnageTrait;

    /**
     * @var
     */
    public $id ;


    /**
     *
     * @data ce commentaire existe
     *
     * @var int sexe
     *
     */
    protected $sexe;


    /**
     * @param $name
     * @param $val
     */
    public function __set($name, $val)
    {
        parent::__set($name, $val);

        if($name == "type"){
            if( is_integer($val)) {

                $this->classe = App::getInstance()->getTable("Game\Item\Item")->find($val);

            }else {
                $this->classe = $val ;
            }
            unset($this->$name);
        }

        if($name == "user_id") {

            $this->player = App::getInstance()->getTable("User")->find($val);
            unset($this->$name);
        }

        if($name == "race_id") {

            $this->race = App::getInstance()->getTable("Game\Item\Item")->find($val);
            unset($this->$name);

        }
        if($name == "faction_id") {

            $this->faction = App::getInstance()->getTable("Game\Item\Item")->find($val);
            unset($this->$name);

        }

        if($name == "position_id") {

            $this->position = App::getInstance()->getTable("Game\Map\Map")->find($val);
            unset($this->$name);

        }
    }

    /**
     * @var UserEntity
     */
    protected $player;

    /**
     * @param string $classe
     * @param $nom
     * @return PersonnageEntity
     */
    public static function createPerso($classe = "archer",$nom):self
    {
        $classe_name = __NAMESPACE__."\Personnage".ucfirst(strtolower($classe))."Entity";

        $perso = new $classe_name();


        if($perso instanceof PersonnageEntity){
            $perso->setType($classe);
            $perso->setName($nom);
        }

        return $perso ;

    }

    /**
     * PersonnageEntity constructor.
     * @param $nom
     */
    public function __construct()
    {
       $this->initPerso();

        $this->initCombattant();
    }

    /**
     * @param mixed $sexe
     * @return PersonnageEntity
     */
    public function setSexe(int $sexe)
    {
        $this->sexe = $sexe;
        return $this;
    }

    /**
     * @return int
     */
    public function getSexe(): int
    {
        return $this->sexe  ;
    }

    public function getId(): int
    {
        return $this->id ;
    }
}