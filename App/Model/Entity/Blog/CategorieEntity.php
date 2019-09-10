<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:25
 */
namespace App\Model\Entity\Blog ;

use Core\Model\Entity\Entity;
use Core\Render\Url;

class CategorieEntity extends Entity
{
    public function getUrl()
    {
        return Url::generate("categorie","article","blog", $this->id );
    }
}