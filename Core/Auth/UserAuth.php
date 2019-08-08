<?php


namespace Core\Auth;

use Core\Model\Entity\Entity;

/**
 * Class UserAuth
 * @package Core\Auth
 */
class UserAuth extends Entity
{
    private $roles  ;
    private $alloweds  ;
    private $forbiddens  ;

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getAlloweds()
    {
        return $this->alloweds;
    }

    /**
     * @param array $alloweds
     */
    public function setAlloweds($alloweds): void
    {
        $this->alloweds = $alloweds;
    }

    /**
     * @return array
     */
    public function getForbiddens()
    {
        return $this->forbiddens;
    }

    /**
     * @param array $forbiddens
     */
    public function setForbiddens($forbiddens): void
    {
        $this->forbiddens = $forbiddens;
    }



}