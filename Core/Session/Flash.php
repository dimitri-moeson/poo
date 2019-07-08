<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 30/04/2019
 * Time: 23:51
 */

namespace Core\Session;


class Flash
{
    /**
     * Design pattern Facade
     * Flash::get()
     * Flash::set()
     * @param $name
     * @param $arguments
     *
     * @return Flash
     */
    public static function __callStatic($name, $arguments): Flash
    {
        // Note : la valeur de $name est sensible à la casse.
        echo "Appel de la méthode statique '$name' "
            . implode(', ', $arguments) . "\n";
        $query = new Flash();
        return call_user_func_array([$query, $name], $arguments);
    }
}