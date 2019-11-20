<?php


namespace App\Model\Entity\Blog;


use Core\Model\Entity\Entity;

class KeywordEntity extends Entity
{

    public function __toString()
    {
        return "".$this->mot ;
    }
}