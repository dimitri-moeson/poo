<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:17
 */
namespace App\Model\Entity\Blog ;

use Core\Model\Entity\Entity;
use Core\Render\Url;

class ArticleEntity extends Entity
{
    /**
     * @return string
     */
    public function getUrl(){

        if($this->type == "article")
        {
            return Url::generate("show", "article","blog", $this->slug);
        }

        if($this->type == "categorie")
        {
            return Url::generate("categorie", "article","blog",$this->slug);
        }

        if($this->type == "page")  return "/".$this->slug ;

        return "/?p=".$this->type."&slug=".$this->slug ;
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