<?php
namespace App\Model\Entity\Game\Combat ;

use App\Model\Entity\Journal;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use Iterator;

/**
 * Class Combat
 * @package App\Combat
 */
class Combat implements Iterator
{
    private $array = array();

    private $defi ;

    private function __construct(Defi $defi) {

        $this->defi = $defi ;
        //Journal::getInstance()->add("nouveau combat<br/>");
        $round = Round::launch($defi,$this);

        $this->position = 0;
        $this->ajoute($round);
        $this->rewind();
    }

    public static function init(Defi $defi){

        return new self($defi);
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
    public function current() :Round {

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
	
    public function ajoute(Round $item){

        $this->array[] = $item ;

        return $this;
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