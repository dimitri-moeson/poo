<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 19:11
 */

namespace App\Controller;


use Core\Database\Query;
use Core\Database\QueryBuilder;

class DefaultController extends AppController
{
    /**
     *
     */
    public function index()
    {
        // echo Query::from('article','a')->where('id = 1')->select('id','titre','contenu') ;
    }
}