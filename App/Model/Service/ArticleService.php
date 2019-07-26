<?php


namespace App\Model\Service;


use App;
use App\Model\Service;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;

class ArticleService extends Service
{
    /**
     * ArticleService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Article");
        $this->loadModel("Blog\Keyword");
        $this->loadModel("Blog\Indexion");
    }

    /**
     * @param $id
     */
    public function key_record($id){

        $keys = explode("," , Post::getInstance()->val("keyword"));
        $keywords = $this->KeywordBase->index($id);

        foreach ($keywords as $key){

            if(!in_array($key,$keys)){

                $this->IndexionBase->clear($key,$id);

            }

        }

        foreach ($keys as $keyword){

            $key = strtolower(trim($keyword));

            if($this->KeywordBase->exists($key)){

                $word = $this->KeywordBase->get(  $key );
                $k_id = $word->id ;

            }else {

                $this->KeywordBase->create( array("mot" => $key ) );
                $k_id = App::getInstance()->getDb()->lasInsertId();
            }

            if(!$this->IndexionBase->exists($k_id,$id)){
                $this->IndexionBase->create( array("article_id" => $id , "keyword_id" => $k_id) );
            }
        }
    }

    /**
     * @param null $id
     */
    public function record($id= null){

        $arr = array(

            "titre"         =>  Post::getInstance()->val("titre"),
            "parent_id"     => (Post::getInstance()->val("parent_id") ?? null ),
            "date"          => (Post::getInstance()->val("date") ?? date("Y-m-d")),
            "contenu"       => (Post::getInstance()->val("contenu") ?? "null" ) ,
            "type"          =>  Post::getInstance()->val("type"),
            "description"   =>  Post::getInstance()->val("description"),
            "default"       => (Post::getInstance()->val("default") ?? 0 ),

        );

        if(is_null($id))
        {
            $this->ArticleBase->create( $arr );

            $id = App::getInstance()->getDb()->lasInsertId();
        }
        else
        {
            $this->ArticleBase->update( $id , $arr );
        }

        $this->ArticleBase->update( $id , array(

            "slug" => $this->slugify($id."-".Post::getInstance()->val("titre"))

        ) );

       $this->key_record($id);

        return true ;
    }
}