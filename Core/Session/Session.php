<?php

namespace Core\Session;

use function _gc;
use Core\Database\Database;
use Core\Database\MysqlDatabase;
use Core\Database\QueryBuilder;
use SessionHandlerInterface;
use SessionIdInterface;
use SessionUpdateTimestampHandlerInterface;

class Session implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface
{
    private static $instance;
    protected $savePath;
    protected $sessionName;
    protected $sessionId;
    var $lifeTime;

    /**
     *
     * @param type $path
     *
     * @return Session
     */
    public static function getInstance($path = null): Session
    {
        if (is_null(self::$instance)) {
            self::$instance = new Session($path);
        }
        return self::$instance;
    }

    /**
     *
     * @param type $path
     */
    private function __construct($path)
    {
        /**$id = md5($this->_getIpForSecure() . date("Ymd"));
        $this->savePath = ROOT . "/tmp/Session/";
        $this->sessionId = $id;
        $this->sessionName = "sess_" . $id;
        $rootDomain = '.primary.fr';
        session_write_close(); //cancel the session's auto start,important
        session_set_save_handler(
            [$this, "open"],
            [$this, "close"],
            [$this, "read"],
            [$this, "write"],
            [$this, "destroy"],
            [$this, "gc"]
        );
        // Ceci permet de prévenir des effets non désirés lors de l'utilisation d'objets
        // comme gestionnaires de session
        register_shutdown_function('session_write_close');
        session_set_cookie_params(
            3600, //$currentCookieParams["lifetime"],
            $this->savePath, // $currentCookieParams["path"],
            $rootDomain,
            1, //$currentCookieParams["secure"],
            1 //$currentCookieParams["httponly"]
        );
        ini_set('session.save_path', $this->savePath);
        ini_set('session.gc_probability', 1);
        session_name($this->sessionName);
        session_id($this->sessionId);
        session_save_path($this->savePath);**/
        session_start();
    }


    function _getIpForSecure()
    {
        if (isset ($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        {
            $IP_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif (isset ($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
        {
            $IP_ADDR = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            $IP_ADDR = $_SERVER['REMOTE_ADDR'];
        }

        // cherche IP serveur et la traite
        $FIRE_IP_ADDR = $_SERVER['REMOTE_ADDR'];
        $ip_resolved  = gethostbyaddr($FIRE_IP_ADDR);
        // construit la chaine d'identification serveur IP
        $FIRE_IP_LITT = ($FIRE_IP_ADDR != $ip_resolved && $ip_resolved) ? $FIRE_IP_ADDR . " - " . $ip_resolved : $FIRE_IP_ADDR;
        // construit la chaine d'identification client IP
        $toReturn = ($IP_ADDR != $FIRE_IP_ADDR) ? "$IP_ADDR | $FIRE_IP_LITT" : $FIRE_IP_LITT;
        return $toReturn;
    }

    /**
     *
     * @param type $right
     *
     * @return string
     */
    function confirm_path($id = null): string
    {
        if (!file_exists($this->savePath) || !is_dir($this->savePath)) {
            if (!mkdir($this->savePath, 0777, true)) {
                die("no directory export");
            }
        }
        if (!is_writable($this->savePath)) //chmod($dir, 0776);
        {
            if (!chmod($this->savePath, 0777)) {
                die("export no writable");
            }
        }
        $file = $this->savePath . "/" . $this->sessionName;
        if (!file_exists($file)) {
            $handler = fopen($file, "w+");
            fwrite($handler, serialize([]));
            fclose($handler);
        }
        return realpath($file);
    }

    /**
     *
     * @param type $savePath
     * @param type $sessionName
     *
     * @return boolean
     */
    function open($savePath, $sessionName): bool
    {
        $this->savePath = $savePath;
        $this->sessionName = $sessionName;
        $this->lifeTime = get_cfg_var("session.gc_maxlifetime");
        $this->confirm_path();
        return true;
    }

    /**
     *
     * @return boolean
     */
    function close(): bool
    {
        $this->confirm_path();
        //fclose($this->handler);
        return true;
    }

    /**
     *
     * @param type $id
     *
     * @return boolean
     */
    function read($id): string
    {
        $file = $this->confirm_path($id);
        if ($file !== false) {
            $tr = file_get_contents($file, FILE_USE_INCLUDE_PATH);
            if (!is_null($tr) && !empty($tr)) {
                $session_data = unserialize($tr);
                if (!is_null($session_data) && !empty($session_data)) {
                    return $session_data;
                }
            }
        }
        //check to see if $session_data is null before returning (CRITICAL)
        return "";//use empty string instead of null!
    }

    /**
     *
     * @param type $id
     * @param type $data
     *
     * @return boolean
     */
    function write($id, $data): bool
    {
        $file = $this->confirm_path($id);
        if ($file !== false) {
            $content = serialize($data);
            if (!is_null($content) && !empty($content)) {
                file_put_contents($file, $content);
            }
        }
        return true;
    }

    /**
     *
     * @param type $id
     *
     * @return boolean
     */
    function destroy($id = null): bool
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
        $file = $this->confirm_path($id);
        if ($file !== false) {
            unlink($file);
        }
        return true;
    }

    /**
     *
     * @param type $maxlifetime
     *
     * @return boolean
     */
    function gc($maxlifetime): bool
    {
        $this->confirm_path();

        if(function_exists()){
            _gc( get_cfg_var("session.gc_maxlifetime")) ;
        }
        foreach (glob($this->savePath . "/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     *
     */
    public function create_sid()
    {
        // available since PHP 5.5.1
        // invoked internally when a new session id is needed
        // no parameter is needed and return value should be the new session id created
        // ...
    }

    /**
     * @param string $sessionId
     * @return bool|void
     */
    public function validateId($sessionId)
    {
        // implements SessionUpdateTimestampHandlerInterface::validateId()
        // available since PHP 7.0
        // return value should be true if the session id is valid otherwise false
        // if false is returned a new session id will be generated by php internally
        // ...
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     * @return bool|void
     */
    public function updateTimestamp($sessionId, $sessionData)
    {
        // implements SessionUpdateTimestampHandlerInterface::validateId()
        // available since PHP 7.0
        // return value should be true for success or false for failure
        // ...
    }


    function generateFormToken($form)
    {
        // generate a token from an unique value, took from microtime, you can also use salt-values, other crypting methods...
        $token = md5(uniqid(microtime(), true));
        // Write the generated token to the session variable to check it against the hidden field when the form is sent
        //$_SESSION[$form.'_token'] = $token;
        $this->setValue($form . '_token', $token);
        //echo $token;
        return $token;
    }

    function verifyFormToken($form, $token = null)
    {
        // check if a session is started and a token is transmitted, if not return an error
        if (!$this->hasValue($form . '_token')) {
            return false;
        }
        // check if the form is sent with token in it
        if (!isset($token)) {
            return false;
        }
        // compare the tokens against each other if they are still the same
        if ($this->getValue($form . '_token') !== $token) {
            return false;
        }
        return true;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}