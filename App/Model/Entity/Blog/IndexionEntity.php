<?php


namespace App\Model\Entity\Blog;


use Core\Model\Entity\Entity;

class IndexionEntity extends Entity
{
    public function __set($name, $val)
    {
        parent::__set($name, $val); 

        if($name == "keyword_id") {

            $this->keyword = \App::getInstance()->getTable("Blog\Keyword")->find($val);
            unset($this->$name);
        }

        if($name == "article_id") {

            $this->article = \App::getInstance()->getTable("Blog\Article")->find($val);
            unset($this->$name);

        }
    }
}