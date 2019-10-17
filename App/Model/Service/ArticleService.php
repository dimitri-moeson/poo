<?php


namespace App\Model\Service;


use App;
use App\Model\Service;
use Core\Auth\DatabaseAuth;
use Core\Database\QueryBuilder;
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

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
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

            if($this->KeywordBase->existsBy( array("mot" => $key ))){

                $word = $this->KeywordBase->get(  $key );
                $k_id = $word->id ;

            }else {

                $this->KeywordBase->create( array("mot" => $key ) );
                $k_id = App::getInstance()->getDb()->lasInsertId();
            }

            if(!$this->IndexionBase->existsBy(array("article_id" => $id , "keyword_id" => $k_id) )){
                $this->IndexionBase->create( array("article_id" => $id , "keyword_id" => $k_id) );
            }
        }
    }

    /**
     * @param null $id
     */
    public function updateContent($id= null)
    {

        if (!is_null($id)) {
            $curr = $this->ArticleBase->find($id);

            $arr = array(

                "contenu"       => Post::getInstance()->val("contenu") ?? $curr->contenu ,

            );

            $this->ArticleBase->update($id, $arr);
        }

    }

    /**
     * @param null $id
     */
    public function record($id= null){

        if(!is_null($id))$curr = $this->ArticleBase->find($id);

         $last = $this->ArticleBase->lastPosition( Post::getInstance()->val("type"));
        $count = $this->ArticleBase->countPosition(Post::getInstance()->val("type"));

        $max = $count->counted > $last->lasted ? $count->counted : $last->lasted ;

        $date = Post::getInstance()->has("date") ? \DateTime::createFromFormat("d/m/Y",Post::getInstance()->val("date"))->format("Y-m-d" ): date("Y-m-d");

        $arr = array(

            "titre"         =>  Post::getInstance()->val("titre"),
            "parent_id"     => (Post::getInstance()->val("parent_id") ?? 0 ),
            "author_id"     => (Post::getInstance()->val("author_id") ?? $this->auth->getUser('id') ),
            "contenu"       => (Post::getInstance()->val("contenu") ?? "null" ) ,
            "type"          =>  Post::getInstance()->val("type"),
            "description"   =>  Post::getInstance()->val("description"),
            "default"       => (Post::getInstance()->val("default") ?? 0 ),
            "menu"          =>  Post::getInstance()->val("menu"),
            "position"      => $curr->position ?? ($max+1),
            "date"          => $date ,

        );

        if(is_null($id))
        {
            $this->ArticleBase->create( $arr );

            $id = App::getInstance()->getDb()->lasInsertId();

            $this->ArticleBase->update( $id , array(

                "slug" => $this->slugify($id."-".Post::getInstance()->val("titre"))

            ) );
        }
        else
        {
            $this->ArticleBase->update( $id , $arr );
        }



       $this->key_record($id);

        return true ;
    }

    /**
     * @param $id
     * @param string $sens
     * @param null $type
     */
    public function setPosition($id,$sens = "+", $type=null ){

        $art =  $this->ArticleBase->find( $id );

        $pos = $art->position;
        $typ = $art->type ?? $type ;

        $last = $this->ArticleBase->lastPosition( $typ ??  Post::getInstance()->val("type"));
        $count = $this->ArticleBase->countPosition($typ ?? Post::getInstance()->val("type"));

        $max = $count->counted > $last->lasted ? $count->counted : $last->lasted ;

        $upd = false ;

        if($sens == "+"){

            $mov = $this->ArticleBase->ranked(($pos+1),$typ);

            if($mov)
            {
                $mov->position--;
                $art->position++;
            }
        elseif(($pos+1 <= $max))
            {
                $art->position++;
                $upd = true ;
            }

        }

        if($sens == "-"){

            $mov = $this->ArticleBase->ranked(($pos-1),$typ);

            if($mov)
            {
                $mov->position++;
                $art->position--;
            }
        elseif(($pos-1 >= 1))
            {
                $art->position--;
                $upd = true ;
            }
        }

        if($mov)
        {
            $this->ArticleBase->update( $mov->id , array("position" => $mov->position ) );
            $this->ArticleBase->update( $art->id , array("position" => $art->position ) );
        }
    elseif($upd)
        {
            $this->ArticleBase->update( $art->id , array("position" => $art->position ) );
        }
    }
}