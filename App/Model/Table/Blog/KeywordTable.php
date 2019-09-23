<?php

namespace App\Model\Table\Blog;

use App\Model\Entity\Blog\KeywordEntity;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

/**
 * Class KeywordTable
 * @package App\Model\Table\Blog
 */
class KeywordTable extends Table
{
    /**
     * list des mots clés present
     *
     * @return array|mixed
     */
    public function cloud($type = 'article'){

        $statement = QueryBuilder::init()->select('k.*','count(i.id) as called')
            ->from('keyword','k')
            ->join('indexion','k.id = i.keyword_id','left','i')
            ->join('article','a.id = i.article_id','left','a')
            ->where('a.type = :type ')
            ->group('k.mot')
            ->order('mot')
        ;

        $array =  $this->request( $statement, array("type" => $type ), false , KeywordEntity::class );

        // TODO : supprimer la génération de doublons ...

        $key = array();

        foreach ($array as $a => $r )
        {
            $mot = trim($r->mot) ;
            $cal = $r->called ;

            if(!isset($key[$mot]))
            {
                $key[$mot] = new KeywordEntity();
                $key[$mot]->id = $r->id;
                $key[$mot]->mot = $mot;
                $key[$mot]->called = 0;
            }

            $key[$mot]->called += $cal ;
        }

        return $key ;
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