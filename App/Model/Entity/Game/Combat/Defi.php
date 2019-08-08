<?php
namespace App\Model\Entity\Game\Combat ;

use App\Model\Entity\Journal;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use ArrayAccess;
use Countable;
use Iterator;

/**
 * Class Combat
 * @package App\Combat
 */
class Defi implements Iterator, ArrayAccess, Countable
{
    private $array = array();
    private $position = 0;
    private $fight ;
    /**
     * Defi constructor.
     */
    public function __construct(Array $array = array()) {
		$this->array = $array;
		$this->fight = Combat::init($this);
        //Journal::getInstance()->add("nouveau defi<br/>");
    }

    /**
     *
     */
    public function rewind() {

        //Journal::getInstance()->add("reinit defi<br/>");
        $this->position = 0;

    }

    /**
     * @return mixed
     */
    public function current() {

        return $this->array[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key():?int {

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

    /**
     * @param PersonnageEntity $item
     * @return $this
     */
    public function ajoute($item){

        $next = $this->position+1;
		$this->array[$next] = $item ;
        return $this ;
    }
	
	public function participants(){
        return $this->array;
    }
	
	public function count(){
        return count($this->array);

	}

	function remove($item){

        $key = array_search($item,$this->array);

            unset($this->array[$key]);
    }

	function has_next()
	{
	    $next = $this->position+1;
        return isset($this->array[$next]);

        //return ($this->position != ($this->count()-1) ? true : false ) ;
	}


    public function end(){
        return end($this->array);
    }

    public function __toString()
    {
        $r = "";

        foreach($this->participants() as $pe)
                $r .= $pe->getName()."<br/>";

        return $r ;
    }

    /**
     * @return Combat
     */
    public function getFight(): Combat
    {
        return $this->fight;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }
}