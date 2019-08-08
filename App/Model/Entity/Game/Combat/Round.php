<?php
namespace App\Model\Entity\Game\Combat ;

use App\Model\Entity\Journal;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use Iterator;

/**
 * Class Round
 * @package App\Combat
 */
class Round implements Iterator
{
    private $array = array();

    private $fight ;

    private function __construct (Defi $defi,Combat $fight ) {

        $this->fight = $fight ;
        $this->position = 0;

        //Journal::getInstance()->add("nouveau round<br/>");
        foreach($defi as $pe ) {
            $passe = Passe::launch($this, $pe);
            $this->ajoute($passe);
        }
        $defi->rewind();
    }

    public static function launch(Defi $defi,Combat $fight){

        return new self( $defi,$fight );
    }

    /**
     *
     */
    public function rewind() {
        $this->position = 0;

    }

    /**
     * @return mixed
     */
    public function current() :Passe {
        return $this->array[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key():int {
        return $this->position;
    }

    /**
     *
     */
    public function next() {
		$this->position++;
    }

    /**
     * @return bool
     */
    public function valid():bool {
        return isset($this->array[$this->position]);
    }

    public function ajoute(Passe $item){

        $this->array[] = $item ;
        return $this ;
    }

    public function list(){
        return $this->array;
    }

    public function end(){
        return end($this->array);
    }
	
	function has_next()
	{
        return (($this->key()+1) != $this->count() ? true : false ) ;
	}

    public function count(){
        return count($this->array);

    }

}