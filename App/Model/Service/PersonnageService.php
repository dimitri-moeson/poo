<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 02/05/2019
 * Time: 06:18
 */

namespace App\Model\Service;

use App\Model\Heritage\Game\Inventaire\InventaireEquipementEntity;
use App\Model\Heritage\Game\Item\ItemEquipementEntity;

use App\Model\Object\Journal;

use App;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Service;
use App\Model\Table\Game\Inventaire\InventaireTable;
use Core\HTML\Form\Form;
use Core\Render\Render;
use Exception;

/**
 * Class PersonnageService
 * @package App\Model\Service
 */
class PersonnageService extends Service
{

    #egion __construct

    /**
     * PersonnageService constructor.
     */
    public function __construct()
    {
        try{

            parent::__construct();

            $this->loadModel("Game\Personnage\Personnage");
            $this->loadModel("Game\Inventaire\Inventaire");
            $this->loadModel("Game\Item\Item");
            $this->loadModel("Game\Map\Map");

            $this->loadService("Map");
            $this->loadService("Inventaire");
            $this->loadService("Item");

        } catch(Exception $e){

           throw $e ;

        }
    }

    #endregion

    /**
     * @return Form
     */
    public function form()
    {
        $form = new Form();

        $form->init("post")->input("name")->textarea("description")->submit("Creer");

        return $form;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function recup($user_id) : ?PersonnageEntity
    {
        try {

            /** @var PersonnageEntity $personnage */
            $personnage = $this->PersonnageBase->findOneBy(array("user_id" => $user_id));

            if ($personnage instanceof PersonnageEntity) {

               $personnage = $this->restor($personnage);

               return $personnage ;
            }
        }
        catch (Exception $e){
            var_dump($e);
        }
    }

    public function restor(PersonnageEntity $personnage ): ?PersonnageEntity{

        if( $this->InventaireBase instanceof InventaireTable ) {
            $sac = $this->InventaireBase->itemListing($personnage->id, self::RUB_PERSO, null , array(), ItemEntity::class );

            foreach ($sac as $composant) {

                if ($composant instanceof ItemEntity) {

                    /** OK **/
                    if ($composant->inventaire_type == self::TYPE_SAC) {
                        $personnage->ajoute($composant);
                    }
                    elseif ($composant->inventaire_type == self::TYPE_RECETTE) {
                        $personnage->assimile($composant);
                    }
                    elseif ($composant->inventaire_type == self::TYPE_QUEST) {
                        $personnage->ajoute($composant);
                    }
                    elseif ($composant->inventaire_type == self::TYPE_SPELL) {
                        $personnage->apprend($composant);
                    }
                }
            }
        }

        $personnage = $this->getEquipement($personnage);
        $personnage = $this->getPosition($personnage);
        $personnage = $this->getCaracteristique($personnage);
        $personnage = $this->getFaction($personnage);
        $personnage = $this->getRace($personnage);
        $personnage = $this->getClasse($personnage);

        return $personnage;
    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     *
     */
    function getCaracteristique(PersonnageEntity $personnage) : PersonnageEntity
    {
        $sql = 'select DISTINCT stat.* ,
		IFNULL((select sum(perso_stat.val)
			from personnage perso
            left join inventaire perso_stat on perso.id = perso_stat.parent_id
                and perso_stat.type in ("'.self::TYPE_STAT.'","'.self::TYPE_RES.'")
                and perso_stat.rubrique = "'.self::RUB_PERSO.'" 
            where stat.id = perso_stat.child_id
                and perso.id = "'.$personnage->getId().'"
		) ,0 )  -- stat personnage
		+
        IFNULL((select sum(obj_stat.val) 
			    from personnage perso
			    left join inventaire equip_inv on perso.id = equip_inv.parent_id 
			        and equip_inv.type = "equipement" 
			        and equip_inv.rubrique = "personnage"
                left join inventaire obj_stat on equip_inv.id = obj_stat.parent_id
                    and obj_stat.type in ("'.self::TYPE_STAT.'","'.self::TYPE_RES.'")
                    and obj_stat.rubrique = "'.self::RUB_OBJ.'" 
                where stat.id = obj_stat.child_id
                and perso.id = "'.$personnage->getId().'"
		) ,0 ) -- stat equipement
		
		as val
	
	from item stat
	where stat.type in ("'.self::TYPE_STAT.'","'.self::TYPE_RES.'")';

        $res = App::getInstance()->getDb()->query($sql,ItemEntity::class);

        foreach ($res as $composant )
        {
            if($composant instanceof ItemEntity)
            {
                if ($composant->getType() == self::TYPE_RES )
                {
                    $personnage = $this->ressource($personnage,$composant);//->getRessources()->ajoute($composant, $composant->id);
                }
                elseif ($composant->getType() == self::TYPE_STAT )
                {
                    $personnage = $this->statistique($personnage,$composant); //->getStats()->ajoute($composant, $composant->id);
                }
            }
        }

        return $personnage;

    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     */
    function getClasse(PersonnageEntity $personnage) :PersonnageEntity
    {
        if(!isset($personnage->classe) or empty($personnage->classe) ) {
            if (!empty($personnage->type)) {
                $map = $this->ItemBase->find($personnage->type);

                $personnage->classe = $map;
            }
        }
        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     */
    function getRace(PersonnageEntity $personnage) :PersonnageEntity
    {
        if(!isset($personnage->race) or empty($personnage->race) ) {
            if (!empty($personnage->race_id)) {
                $map = $this->ItemBase->find($personnage->race_id);

                $personnage->race = $map;
            }
        }
        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     */
    function getFaction(PersonnageEntity $personnage) :PersonnageEntity
    {
        if(!isset($personnage->faction) or empty($personnage->faction) ) {
            if (!empty($personnage->faction_id)) {
                $map = $this->ItemBase->find($personnage->faction_id);

                $personnage->faction = $map;
            }
        }
        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     */
    function getPosition(PersonnageEntity $personnage) : PersonnageEntity
    {
        if($personnage->getPosition() == null) {
            if (!empty($personnage->position_id))
                $map = $this->MapBase->find($personnage->position_id);
            else
                $map = $this->MapService->search(array('x' => 0, 'y' => 0));

            $personnage->setPosition($map);
        }
        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @return PersonnageEntity
     */
    function getEquipement(PersonnageEntity $personnage) : PersonnageEntity
    {
        if($this->InventaireBase instanceof InventaireTable )
        {
            $equip = $this->InventaireBase->itemListing($personnage->id, self::RUB_PERSO, self::TYPE_EQUIP, InventaireEquipementEntity::getPlaces(), ItemEquipementEntity::class );

            foreach ($equip as $composant) {

                if ($composant instanceof ItemEquipementEntity) {

                    $personnage->equip($composant);
                }

            }
        }
        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     */
    function save(PersonnageEntity $personnage)
    {
        $this->loadModel("Game\Personnage\Personnage");
        $this->loadModel("Game\Inventaire\Inventaire");

        $datas = array(

            'name' => "".$personnage->getName(),
            'description' => "".$personnage->getDescription(),
            'img' => $personnage->getImg() ?? "null",
            'status' => $personnage->getStatus() ?? "normal",
            'type' => $personnage->getType() ?? "null",
            'vie' => $personnage->getVie() ?? 100 ,
        );

        //Journal::getInstance()->add( print_r($datas,1) );

        if(!is_null($personnage->getPosition())){

            $datas["position_id"] = $personnage->getPosition()->id ;

        }

        if(isset($personnage->record) && $personnage->record === true )
        {
            if (!is_null($personnage->id)) {
                //Journal::getInstance()->add( "save by update" );

                $this->PersonnageBase->update($personnage->id, $datas);
            }else {
                //Journal::getInstance()->add( "save by create" );
                $this->PersonnageBase->create($datas);
            }
        }

        if($this->InventaireService instanceof InventaireService)
        {
            $this->InventaireService->saveInventaire($personnage->getInventaire(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getSpellBook(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getStats(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getRessources(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getKnows(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getQuestBook(), $personnage->id);
            $this->InventaireService->saveInventaire($personnage->getEquipement(), $personnage->id);
        }
    }

    /**
     * fiche du personnage
     * @param PersonnageEntity $perso
     * @return string
     */
    public function status( PersonnageEntity $personnage = null): ?String
    {
        if ($personnage->deces()) return null;

        return Render::getInstance()->block("perso.status",array(

            "personnage" => $personnage ,
        ));
    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     * @return PersonnageEntity
     */
    function statistique(PersonnageEntity $personnage,ItemEntity $item) : PersonnageEntity
    {
        if(empty($item->parent_id)) $item->parent_id = $personnage->id;
        if(empty($item->inventaire_type)) $item->inventaire_type = self::TYPE_STAT ;
        if(empty($item->inventaire_rubrique)) $item->inventaire_rubrique = self::RUB_PERSO ;
        //if(empty($item->val)) $item->val = 1 ;

        $item->record = false ;

        $personnage->getStats()->ajoute($item);

        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     * @return PersonnageEntity
     */
    function ressource(PersonnageEntity $personnage,ItemEntity $item) : PersonnageEntity
    {
        if(empty($item->parent_id)) $item->parent_id = $personnage->id;
        if(empty($item->inventaire_type)) $item->inventaire_type = self::TYPE_RES ;
        if(empty($item->inventaire_rubrique)) $item->inventaire_rubrique = self::RUB_PERSO ;
        //if(empty($item->val)) $item->val = 1 ;

        $item->record = false ;

        $personnage->getRessources()->ajoute($item);

        return $personnage ;
    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     * @return bool|null
     */
    function craft(PersonnageEntity $personnage, ItemEntity $item )
    {
        $craftable = $personnage->craftable($item);

        if($craftable)
        {
            $recette = $item->getRecette()->getContainer();

            if(!empty($recette))
            {
                foreach ($recette as $compo)
                {
                    $this->perte($personnage,$compo);
                }
            }

            $this->ramasse($personnage, $item);

            //Journal::getInstance()->add($personnage->getName() . " ramasse " . $item->getName() . "<br/>");

            $this->save($personnage);

            return true ;
        }

        return $craftable ;
    }

    /**
     * @param ItemEntity $item
     * @param PersonnageEntity $personnage
     */
    public function perte(PersonnageEntity $personnage, ItemEntity $item )
    {
        $item->parent_id = $personnage->id;
        $item->inventaire_type = self::TYPE_SAC ;
        $item->inventaire_rubrique = self::RUB_PERSO;
        $item->val--;
        $item->record = true ;

        if($item->val === 0 ){

            $personnage->retire($item);

            Journal::getInstance()->add( $personnage->getName() . " retire " . $item->getName() . "<br/>");

            $this->save($personnage);
        }

        return true ;
    }

    /**
     * @param ItemEntity $item
     * @param PersonnageEntity $personnage
     */
    public function ramasse(PersonnageEntity $personnage, ItemEntity $item )
    {
        $item->parent_id = $personnage->id;
        $item->inventaire_id = null ;
        $item->inventaire_type = self::TYPE_SAC ;
        $item->inventaire_rubrique = self::RUB_PERSO;
        $item->record = true ;
        $item->val++;

        if($this->InventaireService instanceof InventaireService) {
            $this->InventaireService->recordItemInInventaire($personnage->id, $item);

            $item->inventaire_id = App::getInstance()->getDb()->lasInsertId();
        }

        if($this->ItemService instanceof ItemService )
            $item = $this->ItemService->generateRandomCharac($item);

        $personnage->ajoute($item);

        Journal::getInstance()->add( $personnage->getName() . " ramasse " . $item->getName() . "<br/>");

        //$this->save($personnage);

        return true ;

    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     */
    public function apprendre(PersonnageEntity $personnage, ItemEntity $item )
    {
        if( !$personnage->getKnows()->disponible($item)) {
            $item->parent_id = $personnage->id;
            $item->inventaire_type = self::TYPE_RECETTE;
            $item->inventaire_rubrique = self::RUB_PERSO;
            $item->val++;
            $item->record = true ;

            $personnage->assimile($item);

            Journal::getInstance()->add($personnage->getName() . " assimile " . $item->getName() . "<br/>");

            $this->save($personnage);
        }
        else{

            Journal::getInstance()->add($personnage->getName() . " connait la recette " . $item->getName() . "<br/>");

        }
    }

    /**
     * @param PersonnageEntity $personnage
     * @param ItemEntity $item
     */
    public function accepte(PersonnageEntity $personnage, ItemEntity $item )
    {
        $item->parent_id = $personnage->id;
        $item->inventaire_type = self::RUB_QUEST;
        $item->inventaire_rubrique = self::RUB_PERSO ;
        $item->val++;
        $item->record = true ;

        $personnage->accepte($item);

        Journal::getInstance()->add( $personnage->getName()." accepte la quete ".$item->getName()."<br/>");

        $this->save($personnage);

    }
}