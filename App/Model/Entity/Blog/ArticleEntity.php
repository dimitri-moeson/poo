<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:17
 */
namespace App\Model\Entity\Blog ;

use Core\Model\Entity\Entity;

class ArticleEntity extends Entity
{

    public function getUrl(){

        return "?p=blog.article.show&id=".$this->id ;
    }

    public function getExtrait(){

        $html = "<p>".substr($this->contenu,0 ,200 )."...</p>";
        $html .= "<a href='".$this->getUrl()."'>Voir la suite</a>";

        return  $html ;
    }

}