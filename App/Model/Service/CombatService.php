<?php


namespace App\Model\Service;


use App\Model\Entity\Game\Combat\Defi;
use App\Model\Entity\Game\Combat\Round;
use App\Model\Entity\Game\CombattantTrait;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Journal;
use App\Model\Service;

class CombatService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel("Game\Personnage\Personnage");

        $this->loadService("Personnage");
    }

    function position (Defi $defi ,$cible){

        foreach ($defi->participants() as $k => $participant) {

            if($participant->id === $cible->id){

                return $k ;
            }

        }

        return false ;
    }

    /**
     * @param Defi $defi
     * @param CombattantTrait $pe
     * @param CombattantTrait $cible
     */
    function attaque(Defi $defi, $pe, $cible){

        Journal::getInstance()->add($pe->getName() . " cible " . $cible->getName() . "<br/>");

        //Journal::getInstance()->add( print_r(array_keys($defi->participants()),1));

        $k = $this->position($defi, $cible) ?? 0 ;

        //Journal::getInstance()->add(" cible " . $cible->getName() . " à la position ".$k."<br/>");

        if ($pe != $cible) {
            $pe->attaque($cible);
            $cible->record = true ;
        } /**else {

            $pe->charge(-1);
            $pe->record = true ;
        }**/


        if ($cible->deces()) {
            $defi->remove($cible);
        }else {
            $defi->offsetSet($k,$cible);

            //Journal::getInstance()->add(" cible " . $cible->getName() . " est réaffecté avec ".$cible->getVie()." PV<br/>");

        }

        //Journal::getInstance()->add($pe->getName() . " enregistre combat contre " . $cible->getName() . "<br/>");

        if($this->PersonnageService instanceof PersonnageService){

            if($pe instanceof PersonnageEntity)
                $this->PersonnageService->save($pe);

            if($cible instanceof PersonnageEntity)
                $this->PersonnageService->save($cible);

        }
    }

    function deroule(Defi $defi){

        $fight = $defi->getFight();

        $participants = $defi->participants();

        $rand_cible = array_rand($participants);
        $cible = $participants[$rand_cible];


        $this->ciblage($defi,$cible);
    }

    function ciblage(Defi $defi,$cible){

        $fight = $defi->getFight();
        $pe = $defi->current();
        $round = $fight->current(); //unserialize($_SESSION['combat']['round']) ;
        $passe = $round->current(); //unserialize($_SESSION['combat']['passe']) ;

        if($pe == $passe->getPerso()) {

            $this->attaque($defi, $pe,$cible);

            $this->suivant($defi , $round , $fight);

        }
        else
        {
            Journal::getInstance()->add("erreur ordre passage...<br/>");
        }
    }

    function suivant($defi = null , $round = null , $fight = null ){

        if ($defi->has_next())// on passe au personnage suivant...
        {
            $defi->next();
            $round->next();
        }
        else // tout les persos ont joué pour ce tour ...
        {
            $round = Round::launch($defi, $fight);
            $fight->ajoute($round)->next();
        }
    }

    function savePerso(PersonnageEntity $personnage)
    {
        $datas = array(

            'name' => $personnage->getName(),
            'description' => $personnage->getDescription(),
            'img' => $personnage->getImg(),
            'status' => $personnage->getStatus(),
            'type' => $personnage->getType(),
            'vie' => $personnage->getVie(),
        );
        if(!is_null( $personnage->id))
            $this->PersonnageBase->update( $personnage->id ,$datas);
        else
            $this->PersonnageBase->create( $datas);

    }
}