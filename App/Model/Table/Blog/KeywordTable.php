<?php


namespace App\Model\Table\Blog;


use App\Model\Entity\Blog\KeywordEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

class KeywordTable extends Table
{
    /**
     * @return array|mixed
     */
    public function cloud(){

        $statement = QueryBuilder::init()->select('k.*','count(i.id) as called')
            ->from('keyword','k')
            ->join('indexion','k.id = i.keyword_id','left','i')
            ->group('k.id')
        ;

        return $this->request( $statement );
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function index($id){

        $statement = QueryBuilder::init()->select('k.*')
            ->from('keyword','k')
            ->join('indexion','k.id = i.keyword_id','left','i')
            ->where('i.article_id = :id')
        ;

        return $this->request( $statement , array("id" => $id), false , KeywordEntity::class);
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key){

        $mot =  $this->get($key);

        if($mot) return true ;

        return false ;
    }

    /**
     * @param $key
     * @return array|mixed
     */
    public function get($key){

        $statement = QueryBuilder::init()->select('k.*')
            ->from('keyword','k')
            ->where('k.mot = :mot');

        return  $this->request( $statement , array("mot" => $key), true, KeywordEntity::class );
    }

}