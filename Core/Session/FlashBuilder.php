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
        $this->session = $session ?? App::getInstance()->getSession();
        if (!is_null($message)) {
            $this->set($message, $type);
        }
    }

    public function set($message, $type = "success")
    {
        $this->session->setValue(
            self::KEY, [
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function get()
    {
        $flash = $this->session->getValue(self::KEY);
        if (!empty($flash)) {
            $this->session->delValue(self::KEY);
            return "<p class='alert " . $flash['type'] . "'>" . $flash['message'] . "</p>";
        }
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->get();
    }

    public static function create($message = null, $type = "success", Session $session = null)
    {
        return new self($message, $type, $session);
    }
}