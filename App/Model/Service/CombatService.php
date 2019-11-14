<?php


namespace App\Model\Service;


use App\Model\Entity\Game\Combat\Combat;
use App\Model\Entity\Game\Combat\Defi;
use App\Model\Entity\Game\Combat\Round;
use App\Model\Entity\Game\CombattantTrait;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Journal;
use App\Model\Service;

class CombatService extends Service
{
    public $suivi;

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

        $k = $this->position($defi, $cible) ?? 0 ;

        $this->suivi .= $pe->getName() . " cible " . $cible->getName() . " à la position ".$k."<br/>";

        if ($pe != $cible) {

            $atk = $pe->getStats()->getScore("offensif");
            $def = $cible->getStats()->getScore("defensif");

            $this->suivi .= "atk =".$atk."<br/>";
            $this->suivi .= "def =".$def."<br/>";

            $pe->attaque($cible);
            $cible->record = true ;
        }

        if ($cible->deces()) {
            $defi->remove($cible);
        }else {
            $defi->offsetSet($k,$cible);

            $this->suivi .= " cible " . $cible->getName() . " est réaffecté avec ".$cible->getVie()." PV<br/>";

        }

        $this->suivi .= $pe->getName() . " enregistre combat contre " . $cible->getName() . "<br/>";

        if($this->PersonnageService instanceof PersonnageService){

            if($pe instanceof PersonnageEntity)
                $this->PersonnageService->save($pe);

            if($cible instanceof PersonnageEntity)
                $this->PersonnageService->save($cible);

        }

        return $defi ;

    }

    function deroule(Defi $defi){

        $fight = $defi->getFight();

        $participants = $defi->participants();

        $rand_cible = array_rand($participants);
        $cible = $participants[$rand_cible];


        $this->ciblage($defi,$cible);

        return $defi ;

    }

    /**
     * @param Defi $defi
     * @param $cible
     */
    function ciblage(Defi $defi,$cible){

        $this->suivi .= "ciblage<br/>";

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
            $this->suivi .= "erreur ordre passage...<br/>";
        }

        $_SESSION['combat']["suivi"] = $this->suivi;

        return $defi ;

    }

    function suivant(Defi $defi = null , Round $round = null , Combat $fight = null ){

        $this->suivi .= "Au suivant ?<br/>";

        if ($defi->has_next())// on passe au personnage suivant...
        {
            $this->suivi .= "personnage suivant<br/>";

            $defi->next();
            $round->next();
        }
        else // tout les persos ont joué pour ce tour ...
        {
            $this->suivi .= "round suivant<br/>";
            $round = Round::launch($defi, $fight);
            $fight->ajoute($round)->next();
        }

        $_SESSION['combat']["suivi"] = $this->suivi;

        return $defi ;
    }

    /**
     * @return mixed
     */
    function resume(){

        $suivi =  $_SESSION['combat']["suivi"];

        unset( $_SESSION['combat']["suivi"]);

        return $suivi ;
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