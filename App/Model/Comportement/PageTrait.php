<?php


namespace App\Model\Comportement;


use Core\Render\Url;

Trait PageTrait
{
    /**
     * @var string $type
     */
    public $type = "default" ;

    /**
     * @return string
     *
     *     public function getUrl()
    {
    return Url::generate("categorie","article","blog", $this->id );
    }
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
}