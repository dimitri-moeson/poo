<?php


namespace App\Model\Comportement;

use App\Model\Entity\Game\Inventaire\InventaireEntity;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;

use Core\Debugger\Debugger;

use App\Model\Heritage\Game\Item\ItemSpellEntity;
use App\Model\Heritage\Game\Item\ItemStatEntity;

use App\Model\Heritage\Game\Inventaire\InventaireEquipementEntity;
use App\Model\Heritage\Game\Inventaire\InventaireKnowledgeEntity;
use App\Model\Heritage\Game\Inventaire\InventaireQuestBookEntity;
use App\Model\Heritage\Game\Inventaire\InventaireSpellBookEntity;
use App\Model\Heritage\Game\Inventaire\InventaireArmementEntity;

trait PersonnageTrait
{
    /** @var  */
    protected $knows ;

    /** @var InventaireEntity  */
    protected $inventaire ;

    /** @var InventaireEntitySpellBook  */
    protected $spellBook ;

    /** @var InventaireEntityQuestBook */
    protected $questBook;
    /**
     * @var InventaireEquipementEntity
     */
    private $equipement;

    public function initPerso(){

        $this->inventaire = new InventaireEntity();
        $this->spellBook = new InventaireSpellBookEntity();
        $this->questBook = new InventaireQuestBookEntity();
        $this->knows = new InventaireKnowledgeEntity();

        $this->equipement = new InventaireEquipementEntity();
        $this->armement = new InventaireArmementEntity();
    }
    /**
     * le personnage perd un objet ...
     * @param ItemEntity $item
     *
     */
    public function retire(ItemEntity $item ){

        if($this->deces()) return null;

        $this->inventaire->retire($item,$item->id);

        return $this ;
    }

    /**
     * Recette de craft
     * @param ItemEntity $spell
     * @return $this|null
     */
    public function assimile(ItemEntity $spell ){

        if($this->deces()) return null;
        //echo "$spell->id isCraftable ??<br/>";

        if($spell->isCraftable()) {
            //echo "$spell->id isCraftable !!<br/>";
            $this->knows->ajoute($spell, $spell->id);
        }
        return $this ;
    }

    /**
     * le personnage gagne un sort
     * @param ItemEntity $item
     * @return $this
     */
    public function apprend(ItemSpellEntity $spell ){

        if($this->deces()) return null;

        if($spell->getType() == "spell") {
            $this->spellBook->ajoute($spell, $spell->id);
        }
        return $this ;
    }

    /**
     * le personnage accepte une quete
     * @param ItemEntity $item => ItemQuestEntity
     * @return $this
     */
    public function accepte(ItemEntity $quest ){

        if($this->deces()) return null;

        if($quest->getType() == "quest") {
            $this->questBook->ajoute($quest, $quest->id);
        }
        return $this ;
    }

    /**
     * le personnage accepte une quete
     * @param ItemEntity $item
     * @return $this
     */
    public function evolve(ItemStatEntity $quest ){

        if($this->deces()) return null;

        Debugger::getInstance()->add( $this->name." accepte la quete ".$quest->getName()."<br/>");

        if($quest->getType() == "stat") {
            $this->stats->ajoute($quest);
        }
        return $this ;
    }

    /**
     * @param $item
     * @return $this|null
     */
    public function equip(ItemEntity $item){

        if($this->deces()) return null;

        if($item->isEquipable()) {
            $this->equipement->equip($item->getType(), $item);
        }
        return $this ;
    }

    /**
     * @param $item
     * @return $this|null
     */
    public function desequip($type){

        if($this->deces()) return null;

        $this->equipement->desequip($type);

        return $this ;
    }
    /**
     * @param ItemEntity $item
     * @return null
     */
    public function craftable(ItemEntity $item)
    {
        if ($this->deces()) return 0 ;

        if ($this->knows->disponible($item))
        {
            Debugger::getInstance()->add( $this->name . " connait la recette " . $item->getName() . "<br/>");

            $recette = $item->getRecette()->getContainer();

            if(!empty($recette))
            {
                foreach ($recette as $x => $compo)
                {
                    //var_dump($compo);
                    if($compo instanceof  ItemEntity) {
                        Debugger::getInstance()->add($this->name . " a besoin de " . $compo->getName() . "<br/>");

                        if (!$this->inventaire->disponible($compo)) {
                            Debugger::getInstance()->add($this->name . " n'a pas " . $compo->getName() . " à sa disposition.");
                            return 2;
                        }
                    }
                    else {

                        return 4;
                    }
                }
            }
            else
            {
                Debugger::getInstance()->add( $this->name . " n'a besoin d'aucun ingrédients <br/>");
            }


            Debugger::getInstance()->add( $this->name . " craft " . $item->getName() . "<br/>");

            return true ;
        }
        else
        {
            Debugger::getInstance()->add( $this->name . " ne connait pas la recette " . $item->getName() . "<br/>");
            return 3 ;
        }

        return 4 ;
    }

    /**
     * le personnage gagne un objet
     * @param ItemEntity $item
     * @return $this
     */
    public function ajoute(ItemEntity $item ){

        if($this->deces()) return null;

        //echo "try perso->ajoute<br/>";
        $this->inventaire->ajoute($item,intval($item->id));
        Debugger::getInstance()->add( $this->name." ajoute ".$item->getName()."<br/>");

        return $this ;
    }

    /**
     * @param ItemEntitySpell $item
     * @param PersonnageEntity|null $cible
     * @param bool $defense
     * @return $this|null
     */
    public function incante(ItemEntitySpell $item , PersonnageEntity $cible = null , $defense = false ){

        if($this->deces()) return null;

        if($this->spellBook->disponible($item)) {
            Debugger::getInstance()->add( "le sort ".$item->getNom()." est dispo dans le grimoire de ".$this->getName().".<br/>");
            if (is_null($cible)) {
                Debugger::getInstance()->add( $this->getName()." s'applique à lui-meme le sort ".$item->getNom()."<br/>");
                $item->affecte($this, $defense);
            } else {
                Debugger::getInstance()->add( $this->getName()." applique à sa cible ".$cible->getName()." le sort ".$item->getNom()."<br/>");

                $item->affecte($cible, true);
            }
            $this->charge((-1*$item->getConsommation()));

        }
        else
        {
            Debugger::getInstance()->add( "le sort n'est pas disponible...<br/>");
        }
        return $this ;
    }

    /**
     * @param ItemEntity $item
     * @param PersonnageEntity|null $cible
     * @param bool $defense
     * @return $this|null
     */
    public function utilise(ItemEntity $item , PersonnageEntity $cible = null , $defense = false ){

        if($this->deces()) return null;

        if($this->inventaire->disponible($item)) {
            Debugger::getInstance()->add( "l'item ".$item->getName()." est dispo dans l'inventaire de ".$this->getName().".<br/>");
            if (is_null($cible)) {
                Debugger::getInstance()->add( $this->getName()."s'applique à lui-meme l'item ".$item->getName()."<br/>");
                $item->affecte($this, $defense);
            } else {
                Debugger::getInstance()->add( $this->getName()."applique à sa cible ".$cible->getName()." l'item ".$item->getName()."<br/>");

                $item->affecte($cible, false);
            }
            $this->retire($item);
        }
        else
        {
            Debugger::getInstance()->add( "l'item  ".$item->getName()." n'est pas disponible dans l'inventaire de ".$this->getName()." ...<br/>");
        }
        return $this ;
    }

    /**
     * @return InventaireEntityQuestBook
     */
    public function getQuestBook(): InventaireQuestBookEntity
    {
        return $this->questBook;
    }

    /**
     * @return InventaireEntitySpellBook
     */
    public function getSpellBook(): InventaireSpellBookEntity
    {
        return $this->spellBook;
    }

    /**
     * @return InventaireEntity
     */
    public function getInventaire(): InventaireEntity
    {
        return $this->inventaire;
    }

    /**
     * @return mixed
     */
    public function getKnows() : InventaireKnowledgeEntity
    {
        return $this->knows;
    }

    /**
     * @return InventaireEquipementEntity
     */
    public function getEquipement(): InventaireEquipementEntity
    {
        return $this->equipement;
    }

    /**
     * @return InventaireEquipementEntity
     */
    public function getArmement(): InventaireArmementEntity
    {
        return $this->armement;
    }
}