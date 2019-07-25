<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 30/04/2019
 * Time: 23:50
 */

namespace Core\Session;


class FlashBuilder
{
    private $session;
    const KEY = 'flash';

    public function __construct($message = null, $type = "success", Session $session = null)
    {
        $this->session =  $_SESSION[self::KEY] ?? array();//$session ?? App::getInstance()->getSession();
        if (!is_null($message)) {
            $this->set($message, $type);
        }
    }

    public function set($message, $type = "success")
    {
       /** $this->session->setValue(
            self::KEY, [
            'message' => $message,
            'type' => $type,
        ]);**/

        $_SESSION[self::KEY] =  [
            'message' => $message,
            'type' => $type,
        ] ;
    }

    public function get()
    {
        $flash = $_SESSION[self::KEY] ?? array(); //$this->session->getValue(self::KEY);
        if (!empty($flash)) {
            $_SESSION[self::KEY] = array(); // $this->session->delValue(self::KEY);
            return "<div class='alert alert-" . $flash['type'] . "'>" . $flash['message'] . "</div>";
        }
    }

    public function __toString()
    {
        return $this->get();
    }

    public static function create($message = null, $type = "success", Session $session = null)
    {
        return new self($message, $type, $session);
    }
}