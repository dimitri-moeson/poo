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
    /**
     * @return string
     */
    public function getUrl(){

        if($this->type == "page")  return "/?slug=".$this->slug ;

        if($this->type == "article")  $type = "blog.article.show";
        if($this->type == "categorie")  $type = "blog.article.categorie";

        return "/?p=".$type."&slug=".$this->slug ;
    }

    /**
     * @return string
     */
    public function getExtrait(){

        $html = "<p>".$this->description."</p>";
        $html .= "<p>".substr($this->contenu,0 ,200 )."...</p>";
        $html .= "<a href='".$this->getUrl()."'>Voir la suite</a>";

        return  $html ;
    }
}