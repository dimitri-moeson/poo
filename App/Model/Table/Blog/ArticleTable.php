<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 17:29
 */
namespace App\Model\Table\Blog ;

use Core\Database\Query;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

/**
 * Class ArticleTable
 * @package App\Model\Table
 */
class ArticleTable extends Table
{
    public function all(){

        $statement = QueryBuilder::init()->select('a.*', 'c.nom as cat_titre')
            ->from('article','a')
            ->join('categorie','c.id = a.categorie_id','left','c');

        /**
         * "
        select a.*, c.nom as cat_titre
        from article a
        left join categorie c ON c.id = a.categorie_id
        "
         */
        return $this->request( $statement );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getListByCategorie($id){

        $statement = QueryBuilder::init()->select('a.*', 'c.nom as cat_titre')
            ->from('article','a')
            ->join('categorie','c.id = a.categorie_id','left','c')
            ->where(' c.id = :id ')
        ;
        /**
         * "
        select a.*, c.nom as cat_titre
        from article a
        left join categorie c ON c.id = a.categorie_id
        where c.id = :id
        "
         */
        return $this->request( $statement ,array("id" => $id));
    }



}