<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 27/04/2019
 * Time: 17:29
 */
namespace App\Model\Table\Blog ;

use App\Model\Entity\Blog\ArticleEntity;
use Core\Database\Query;
use Core\Database\QueryBuilder;
use Core\Model\Table\Table;

/**
 * Class ArticleTable
 * @package App\Model\Table
 */
class ArticleTable extends Table
{
    public function recent($type){

        $statement = QueryBuilder::init()->select('a.id','a.titre')
            ->from('article','a')
            ->where('a.type = :type ')
            ->orders("date DESC");

        return $this->request( $statement, array('type' => $type),false,ArticleEntity::class );
    }

    public function recup($slug){

        $statement = QueryBuilder::init()->select('a.*')
            ->from('article','a')
            ->where('a.slug = :slug ')
            ->orders("date DESC");

        return $this->request( $statement, array('slug' => $slug),true,ArticleEntity::class );
    }

    public function default(){

        $statement = QueryBuilder::init()->select('a.*')
            ->from('article','a')
            ->where('a.type = "page" ')
            ->where('a.default = 1 ')
            ->orders("a.id DESC");

        return $this->request( $statement, null ,true,ArticleEntity::class );
    }

    public function all(){

        $statement = QueryBuilder::init()->select('a.*')/** , 'c.nom as cat_titre' */
            ->from('article','a')
        //    ->join('categorie','c.id = a.categorie_id','left','c')
        ;

        return $this->request( $statement );
    }

    /**
     * @return QueryBuilder
     */
    private function queryType(){

        return QueryBuilder::init()->select('a.*')->from('article','a')->where('a.type = :type') ;
    }

    /**
     * @param $type
     * @return array|mixed
     */
    public function allOf($type){

        return $this->request( $this->queryType() ,array("type" => $type),false,ArticleEntity::class );
    }

    /**
     * @param $type
     * @return array
     */
    public function listing($type){

        return $this->list("id","titre", $this->queryType() ,array("type" => $type));

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getListByCategorie($id, $type = "article"){

        $statement = QueryBuilder::init()->select('a.*', 'c.titre as cat_titre')
            ->from('article','a')
            ->where('a.type = :type ','a.parent_id = :id')
            ->join('article','c.id = a.parent_id','left','c')
            ->where(' c.id = :id ')
        ;

        return $this->request( $statement ,array("id" => $id,"type" => $type),false,ArticleEntity::class);
    }



}