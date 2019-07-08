<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:25
 */
namespace App\Model\Entity\Blog ;

use Core\Model\Entity\Entity;

class CategorieEntity extends Entity
{
    public function getUrl(){

        return "Index.php?p=blog.article.categorie&id=".$this->id ;
    }
}