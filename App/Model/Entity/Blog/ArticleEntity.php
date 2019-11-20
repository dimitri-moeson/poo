<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 22:17
 */
namespace App\Model\Entity\Blog ;

use App\Model\Entity\UserEntity;
use App\Model\Table\Blog\ArticleTable;
use Core\Model\Entity\Entity;
use Core\Render\Url;

class ArticleEntity extends Entity
{
    /**
     * @var string $type
     */
    public $type = "default" ;

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
     *
     * texte raccourci...
     * @return string
     */
    public function getExtrait(){

        $html = "<p>".$this->description."</p>";
        $html .= "<p>".substr($this->contenu,0 ,200 )."...</p>";
        $html .= "<a href='".$this->getUrl()."'>Voir la suite</a>";

        return  $html ;
    }

    public function __set($name, $val)
    {
        parent::__set($name, $val);

        if($name == "author_id") {

            $this->author = \App::getInstance()->getTable("User")->find($val);
            unset($this->$name);
        }

        if($name == "parent_id") {

            $this->parent = \App::getInstance()->getTable("Blog\Article")->find($val);
            unset($this->$name);

        }
    }
}