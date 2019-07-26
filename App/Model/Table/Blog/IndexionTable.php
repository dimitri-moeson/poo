<?php


namespace App\Model\Table\Blog;


use App\Model\Entity\Blog\IndexionEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

class IndexionTable extends Table
{
    /**
     * @param $k_id
     * @param $a_id
     * @return bool
     */
    public function exists($k_id,$a_id){

        $statement = QueryBuilder::init()->select('i.*')
            ->from('indexion','i')
            ->where('i.keyword_id = :k_id','i.article_id = :a_id');

        $mot =  $this->request( $statement , array("k_id" => $k_id,"a_id" => $a_id), true );

        if($mot) return true ;

        return false ;
    }

    /**
     * @param $key
     * @param $a_id
     * @return bool
     */
    public function clear($key,$a_id){

        $statement = QueryBuilder::init()->select('i.*')
            ->from('indexion','i')
            ->join('keyword','k.id = i.keyword_id','left','k')
            ->where('k.mot = :mot','i.article_id = :a_id');

        $mot = $this->request( $statement , array("mot" => $key,"a_id" => $a_id), true , IndexionEntity::class );

        if($mot) $this->archive($mot->id);

        return false ;

    }

}