<?php
namespace App\Model\Entity ;

class Journal
{
    private $history;

    private static $instance ;

    /**
     * Journal constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)){

            self::$instance = new Journal();
        }

        return self::$instance ;
    }

    /**
     * @param $str
     */
    public function add($str){

        $this->history .= $str ;

    }

    /**
     * @return mixed
     */
    public function view()
    {
        return $this->history ;
    }
}